# Release plan

Release lines, support periods and the scope of each major version of
`ddate/ddate`. Decided 2026-10-25 by the maintainer. Update this file when the
plan changes.

## Overview

| Version | Branch | PHP           | Scope                                     | Support                                                        |
|---------|--------|---------------|-------------------------------------------|----------------------------------------------------------------|
| 1.1.x   | `1.1`  | 5.3 to 8.5    | stable, feature complete                  | bug and security fixes until 2029-12-31 (end of PHP 8.5 security support) |
| 2.0.x   | `main` | 8.6 and newer | modernised implementation, **same API as 1.1** | released together with (or as close as possible to) PHP 8.6     |
| 3.0     | later  | tbd           | API changes and BC breaks                 | not scheduled                                                  |

## 1.1: stable, bug and security fixes only

- Supported with bug and security fixes until PHP 8.5 no longer receives
  official security fixes, planned for **2029-12-31**.
- Otherwise 1.1 is stable and feature complete: no new features, no
  refactorings, and in particular **no API-breaking changes**.
- Fixes are made on the `1.1` branch and released as `v1.1.x` tags on `1.1`
  (see AGENTS.md on that branch for the PHP 5.3 coding rules).
- 1.1 is the only line for users on PHP 5.3 to 8.5.

## 2.0: modernise only, no API changes

- Supported PHP versions: **PHP 8.6 and newer** only. Composer constraint
  `^8.6`: once a new major PHP version (9.0) is released and tested, a 2.x
  minor release widens it (e.g. `^8.6 || ^9.0`).
- Tests use PHPUnit 13.
- Release 2.0.0 at the same time as PHP 8.6, or as close to it as possible.
- Scope: modernise the implementation so it takes advantage of current PHP
  language features, without changing the API. Upgrading from 1.1 to 2.0
  must require nothing but PHP 8.6.
- Developed on `main`, released as `v2.*` tags on `main`.

### What "no API changes" means

1.1 marks nothing as `@api` or `@internal` and declares no class `final`, so
every public class, interface, method, constant and property in
`EmperorNortonCommands\lib` is treated as API. In 2.0:

- Class, interface and namespace names stay the same. Switching from PSR-0 to
  PSR-4 autoloading is fine as long as the fully qualified names do not
  change.
- Public and protected method signatures stay compatible: same names, same
  parameters and defaults. Parameter types may be added only if they accept
  every value 1.1 accepts (e.g. `?string` where `null` is allowed).
- No return types on methods that subclasses may override (a subclass
  without the return type would fail with a fatal error); no new `final`
  classes or methods; no removed or renamed public or protected members.
- The output of `Ddate::ddate()` and of `bin/ddate`, the format string
  fields, the `dmY` date argument, the locales and the CLI options stay the
  same. Fixing an actual bug in the output is allowed but must be documented
  in the release notes.
- Allowed: `declare(strict_types=1)` in library files, types on private
  members and internal code, readonly and typed private properties, `match`,
  enums and other new language features in internal code, new PHPUnit,
  static analysis, CI and tooling changes.

When in doubt whether a change breaks the API, it belongs in 3.0.

## 3.0: API changes (later)

- API changes and BC breaks that were mixed into the previous 2.x work
  (`a2bf1bd`, branch `dev/refactoring-and-improvement`) are deferred to 3.0.
  Examples: moving classes to `Internal/` or `Formatter/`, new public types
  (`DiscordianDate/*`, `XDay`, `Locale`), removing members from `Value`,
  making classes `final`, marking API with `@api`/`@internal` (#59).
- Not scheduled yet. When 3.0 development starts on `main`, a `2.x`
  maintenance branch is created from the last 2.x release; the support period
  for 2.x is decided then.

Security vulnerabilities are reported and handled as described in
[SECURITY.md](../SECURITY.md).

## Releasing 1.1.x next to 2.x

- 1.1.x releases are created with `--latest=false`, so "Latest" on GitHub
  (and the PHAR at `/releases/latest`) stays with the current major version.
  As long as 2.0.0 is not released, mark a new 1.1.x release as "Latest" by
  hand after the release job: `gh release edit v1.1.x --latest`.
- Both release jobs check that the tag is on their branch (`RELEASE_BRANCH`).
- The README on `1.1` announces the end of life of 1.1 and how to allow both
  versions (`"^1.1 || ^2.0"`); publish it together with 2.0.0.

## Open points

- CI and `.docker/run-tests.sh` use PHP 8.6 pre-release builds (setup-php
  nightly, `8.6-rc-cli` image); switch to the final 8.6.0 when it is released.
- Bug fixes that change output go into 1.1.x as well, so the 1.1.x release
  next to 2.0.0 (1.1.6) and 2.0.0 produce the same output. Done: German
  ordinals, holyday names, help text and CLI (#72 for `1.1`, released in
  1.1.5; #71 for `main`). Still to review: updated holyday data from the
  previous `main` (`2fe4715`).
- Support table in README.md on `main`.
- Enable private vulnerability reporting on GitHub (referenced by
  SECURITY.md).
