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

| Branch | Purpose                                                                                         |
|--------|-------------------------------------------------------------------------------------------------|
| `main` | Default branch on GitHub. Development of 2.0 (PHP 8.6+, same API as 1.1), tags `v2.*`.          |
| `1.1`  | 1.1.x line (latest tag `v1.1.4`), bug and security fixes until 2029-12-31. PHP 5.3 to 8.5.      |

Release lines, support periods and the scope of 2.0 and 3.0 are defined in
[docs/release-plan.md](docs/release-plan.md). Read it before changing code on
`main`.

Releases are git tags (`vX.Y.Z`); Packagist picks them up automatically.

- Tag on `main` and push the tag (`git push origin vX.Y.Z`). CI then runs the
  tests, builds the PHAR, attests it and creates the GitHub release with
  `ddate.phar` attached. Do not create the release in the GitHub UI first.
- The release job checks that the tagged commit is on `RELEASE_BRANCH` (set
  in the workflow) and fails before anything is published otherwise.
  Packagist publishes every pushed tag regardless, so check the branch before
  pushing a tag.
- Packagist treats stable versions as immutable: moving or re-creating a tag
  does not change the published version. A wrong release can only be fixed
  with a new version number.
- `v1.1.2` on Packagist points to code of the previous `main` (PHP 8.3) by
  mistake and cannot be corrected; `v1.1.3` contains the intended 1.1.2 code.

## 2.0 development

`main` was restarted from `1.1` (`v1.1.4`) with merge commit `22eb45a`. Its
content is identical to `1.1`; the previous `main` (`a2bf1bd`) is the first
parent of that merge and stays available in the history. 2.0 changes are
ported step by step, each in its own pull request with green CI.

**2.0 modernises the implementation for PHP 8.6+ without changing the API**;
API changes and BC breaks belong in 3.0. See "What 'no API changes' means" in
[docs/release-plan.md](docs/release-plan.md). Sources for porting:

- previous `main` (`a2bf1bd`): PHPUnit 11, PSR-4, dev tooling, updated
  holyday data, German translation fix
- branch `dev/refactoring-and-improvement` (based on the previous `main`):
  `strict_types`, refactored tests (#58); its new structure (`Internal/`,
  `DiscordianDate/`, `XDay`, `Locale`), `final` classes and `@api`/`@internal`
  (#59) change the API and are deferred to 3.0

Port deliberately, not wholesale. Output strings of `Ddate::ddate()` are API.

`main` requires PHP `^8.6` and PHPUnit 13. The source code is still the 1.1
code; modernising it is the work for 2.0 (see "Coding rules for 2.0").

## Repository layout

```
bin/ddate                                  CLI entry point (also PHAR entry point)
docs/release-plan.md                       release lines, support periods, scope of 2.0/3.0
SECURITY.md                                supported versions, reporting vulnerabilities
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
.github/workflows/continuous-integration.yml   GitHub Actions CI (PHP 8.6), PHAR build and release
box.json                                   PHAR configuration for Box
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
.docker/run-tests.sh 8.6
```

The script copies the project to `build/test-php-<version>/`, resolves
dependencies there with a Composer platform override, then runs PHPUnit and a
CLI smoke test in a Docker container for that PHP version. The project's own
`vendor/` is left untouched. Images come from `mirror.gcr.io` (set
`DOCKER_REGISTRY=docker.io` to change) and are extended locally with mbstring,
which PHPUnit needs and the official 8.6 images no longer include. Until PHP
8.6.0 is released, 8.6 runs on the `8.6-rc-cli` image; CI uses setup-php's
nightly build of the PHP-8.6 branch.

`phpunit.xml.dist` sets `failOnDeprecation`, `failOnNotice` and
`failOnWarning`: any PHP deprecation, notice or warning fails the build. CI
runs the same tests on GitHub Actions.

## PHAR

CI builds `ddate.phar` with [Box](https://github.com/box-project/box) on
every run (job `phar`, workflow artifact `ddate-phar`) and runs it on PHP 8.6
(job `phar-test`). For a `v*` tag, job `release` adds a GitHub artifact
attestation (Sigstore build provenance) and uploads the PHAR to the release.
PHARs are not signed with GPG.

- Box generates the autoloader and adds its requirement checker, so the PHAR
  refuses to run on PHP < 8.6 with a clear message.
- `box.json` lists the PHAR contents explicitly (`src/.../lib`, `LICENSE`), so
  tests never end up in it. No compression, so `ext-zlib` is not required.
- Box is pinned to a version and SHA-256 checksum in the workflow.

To build locally, in a copy of the project without dev dependencies:

```sh
composer update --no-dev
box compile
php build/phar/ddate.phar
```

## Coding rules for 2.0

2.0 modernises the implementation for PHP 8.6 **without changing the API**.
Read "What 'no API changes' means" in
[docs/release-plan.md](docs/release-plan.md) before changing any public or
protected class, method or property. In short:

- Internal code may use any PHP 8.6 feature (`declare(strict_types=1)`,
  typed and readonly private properties, `match`, enums, ...).
- Public and protected signatures stay compatible: no renamed or removed
  members, parameter types only if they accept every value 1.1 accepts, no
  return types on methods subclasses may override, no new `final`.
- Output of `Ddate::ddate()` and `bin/ddate` stays the same.

Tests use PHPUnit 13:

- attributes, not docblock annotations: `#[DataProvider('name')]`,
  `#[Before]` (annotations are ignored since PHPUnit 12)
- data providers are `public static`
- every test needs at least one assertion

Style: 4 spaces, LF, Allman braces for classes/methods/control structures as
in the existing code, docblocks on classes and methods (see `.editorconfig`).

## Conventions

- Commit messages use [gitmoji](https://gitmoji.dev) codes, e.g.
  `:bug: Fix ...`, `:recycle: ...`, `:construction_worker: ...` (CI),
  `:memo: ...` (docs), `:white_check_mark: ...` (tests).
- Branch names: `dev/<topic>`.
- Discordian flavour in docs and texts is intentional; keep it.
