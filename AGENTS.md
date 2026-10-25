# AGENTS.md

Guidance for AI coding agents (Claude Code, Codex, Copilot, ...) working on
this repository. Human contributors may find it useful, too.

## What this is

`ddate/ddate` is a small PHP library (plus a CLI script) that converts
Gregorian dates to Discordian dates, an "almost faithful" PHP recreation of
the `ddate` command once shipped with util-linux. Published on Packagist as
[`ddate/ddate`](https://packagist.org/packages/ddate/ddate), source on
[GitHub](https://github.com/dweichert/Ddate). Public domain (Unlicense).

## Branches and release lines

| Branch | Purpose                                                                                       |
|--------|-----------------------------------------------------------------------------------------------|
| `1.1`  | Maintenance line for 1.1.x (latest tag `v1.1.3`, next `v1.1.4`). PHP 5.3 to 8.5.                |
| `2.x`  | Default branch on GitHub. Development of 2.0: modernised code, PHP 8.3+, PHPUnit 11, PSR-4.     |

Releases are git tags (`vX.Y.Z`); Packagist picks them up automatically.

- Tag on the release branch (`1.1`), never on `2.x`. When creating a release
  in the GitHub UI, pick the existing tag or set *Target* to `1.1`: the
  target defaults to `2.x`.
- Packagist treats stable versions as immutable: moving or re-creating a tag
  does not change the published version. A wrong release can only be fixed
  with a new version number.
- `v1.1.2` on Packagist points to `2.x` code (PHP 8.3) by mistake and cannot
  be corrected; `v1.1.3` contains the intended 1.1.2 code.

**This file describes the `1.1` branch.** The rules below about PHP 5.3
compatibility do not apply to `2.x`.

## Goals for 1.1.x

- Preserve existing behaviour and public API. Output strings of `Ddate::ddate()`
  are effectively API: do not change them.
- Stay compatible with every PHP version from 5.3 to 8.5. The code must run
  without deprecation notices on the newest PHP versions.
- Only modernise as far as needed for that. Larger refactorings belong in 2.0.

## Repository layout

```
bin/ddate                                  CLI entry point (also PHAR entry point)
src/EmperorNortonCommands/lib/             library code (PSR-0, namespace EmperorNortonCommands\lib)
  Ddate.php                                public facade: ddate($format, $date, $locale)
  Converter.php                            Gregorian DateTime -> Value
  Value.php                                Discordian date value object
  Formatter.php, FormatterFactory.php      format strings (%A, %B, %e, %H, %{ %} ...)
  locale/{en,de}/StandardFormatter.php     locale specific formatters
  locale/{en,de}/data/*.xml                holyday data, validated by xml-schema/holydays.xsd
  Holydays.php, Holydays/*                 holyday sets (XML based) and computed holydays
src/EmperorNortonCommands/tests/           PHPUnit tests (live under src/, not tests/)
.docker/run-tests.sh                       runs the test suite on every supported PHP version
.github/workflows/continuous-integration.yml   GitHub Actions CI (PHP 5.3 to 8.5)
```

`.tools/`, `vendor/`, `build/` and `composer.lock` are not committed on this
branch.

## How it works

1. `Ddate::ddate()` parses the optional `dmY` date string into a UTC `DateTime`
   (current date if omitted).
2. `Converter::convert()` returns a `Value` (season, day, weekday, YOLD year,
   days until X-Day). St. Tib's Day (29 Feb) is a special case
   (`Value::ST_TIBS_DAY`).
3. `FormatterFactory` picks a locale formatter (`en` fallback); the formatter
   expands the format string and looks up holydays. `%1` to `%4` toggle holyday
   sets, which are read from the locale's XML files or computed (Erister, Fun
   Friday, ...).

## Testing

```sh
# all supported PHP versions (needs Docker, Composer and rsync on the host)
.docker/run-tests.sh
# selected versions
.docker/run-tests.sh 5.3 8.5
```

The script copies the project to `build/test-php-<version>/`, resolves
dependencies there with a Composer platform override (Composer 2.2 LTS for
PHP < 5.6), then runs PHPUnit and a CLI smoke test in a Docker container
for that PHP version. The project's own `vendor/` is left untouched. Images
come from `mirror.gcr.io` (set `DOCKER_REGISTRY=docker.io` to change);
PHP 5.3/5.4 use `devilbox/php-fpm-*` images because the official ones can no
longer be pulled.

No `composer.lock` is committed, so Composer installs the newest compatible
PHPUnit: 4.8 (PHP 5.3-5.5), 5.7 (5.6), 6.5 (7.0), 7.5 (7.1), 8.5 (7.2),
9.6 (7.3+). Old PHPUnit versions have security advisories, so Composer >= 2.9
needs `COMPOSER_NO_SECURITY_BLOCKING=1` (dev-only dependency).

`phpunit.xml.dist` sets `convertDeprecationsToExceptions="true"`: any PHP
deprecation fails the build. CI runs the same matrix on GitHub Actions.

## Coding rules for the 1.1 branch

Code (library *and* tests) must parse and run on PHP 5.3. Do not use:

- short array syntax `[]`, traits, `$this` in closures (5.4)
- `::class`, `finally`, generators, `DateTimeInterface` at runtime (5.5)
- variadics, `**`, `use function` (5.6)
- scalar type declarations, return types, `??`, `<=>`, anonymous classes (7.0)
- nullable types `?Foo`, `void`, `iterable` (7.1), anything newer
- `(integer)`, `(boolean)`, `(double)` casts (deprecated in 8.5): use `(int)`, `(bool)`, `(float)`
- implicitly nullable parameters `Foo $foo = null` (deprecated in 8.4): leave
  the parameter untyped and check `instanceof` instead (see `Ddate::__construct()`)
- passing `null` to internal functions expecting strings (deprecated in 8.1)

Tests must work with PHPUnit 4.8 through 9.6:

- extend `PHPUnit\Framework\TestCase` (namespaced alias exists since 4.8.35)
- no `setUp()`/`tearDown()` overrides (the `void` return type in PHPUnit 8+
  cannot be written in PHP 5); use a public method annotated with `@before`
- no `expectException()` / `setExpectedException()`; use `try`/`catch` plus
  `self::fail()`
- use docblock annotations (`@dataProvider`, `@before`), not attributes
- prefer real objects over mocks (mock builder APIs differ between versions)
- every test needs at least one assertion (risky tests in PHPUnit 6+)

Style: 4 spaces, LF, Allman braces for classes/methods/control structures as
in the existing code, docblocks on classes and methods (see `.editorconfig`).

## Conventions

- Commit messages use [gitmoji](https://gitmoji.dev) codes, e.g.
  `:bug: Fix ...`, `:recycle: ...`, `:construction_worker: ...` (CI),
  `:memo: ...` (docs), `:white_check_mark: ...` (tests).
- Branch names: `dev/<topic>`.
- Discordian flavour in docs and texts is intentional; keep it.
