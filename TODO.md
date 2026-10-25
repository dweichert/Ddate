# TODO: release v2.0.0

Checklist for 2.0.0, planned for the PHP 8.6 release (19 November 2026).
**Delete this file before merging this branch into `main`.** Scope and rules:
[docs/release-plan.md](docs/release-plan.md) (modernise only, no API
changes, PHP 8.6+).

Release 2.0.0 first, then v1.1.7 (see `TODO.md` on branch
`dev/release-1.1.7`).

## Before the release day

- [ ] Branch `dev/release-plan` (release plan, SECURITY.md) is merged into
      `main`.
- [ ] Modernisation ported to `main`, each change in its own pull request
      with green CI. Nothing from the "3.0" list in the release plan.
- [x] Composer constraint `^8.6`, PHPUnit 13, CI and `.docker/run-tests.sh`
      on PHP 8.6 only, `box.json` without the old-PHP workarounds, AGENTS.md
      coding rules for 2.0 (branch `dev/php-8.6`, merged into `main`).
- [ ] Output of `Ddate::ddate()` and `bin/ddate` is identical to 1.1:
      `.docker/compare-with-1.1.sh` (newest v1.1.* tag) and
      `.docker/compare-with-1.1.sh origin/1.1` (fixes for 1.1.7 not yet
      tagged) report no differences.
- [ ] README on `main`: install with `"^2.0"`, PHP 8.6 requirement, release
      table (2.0.x current, 1.1.x until 31 December 2029, 1.0.x end of
      life), PHAR section (PHAR requires PHP 8.6), upgrade note (only PHP
      8.6 needed, `"^1.1 || ^2.0"` for projects that support older PHP).
- [ ] Private vulnerability reporting is enabled on GitHub (SECURITY.md
      links to it): `gh api repos/dweichert/Ddate/private-vulnerability-reporting`
      shows `"enabled":true`.

## On the release day

- [ ] PHP 8.6.0 is released (php.net). Switch from the pre-release builds
      to 8.6.0: `.docker/run-tests.sh` (`8.6-rc-cli` image to `8.6-cli`), the
      comments "nightly build ... until 8.6.0 is released" in the workflow,
      AGENTS.md (Testing) and the open point in `docs/release-plan.md`.
      Re-run CI on `main` so it tests against the final 8.6.0.
- [ ] README: "last updated" date is the actual release day. Compute it with
      `php bin/ddate '%A, %e of %B, %Y' DDMMYYYY`
      (19 November 2026 = Pungenday, 31st of The Aftermath, 3192).
- [ ] `docs/release-plan.md` and `SECURITY.md` still match the plan (dates,
      supported versions, open points resolved).
- [ ] Delete `TODO.md` (`git rm TODO.md`), commit, push.
- [ ] Pull request into `main`, CI green, merge with "Create a merge commit".
- [ ] Tag on `main` with `git tag` and push the tag. Do **not** create the
      tag or the release in the GitHub release form. The release guard
      accepts only `v2.*` tags on `main` (#73).
      ```sh
      git fetch origin
      git log --oneline -1 origin/main    # the merge of this branch
      git tag -a v2.0.0 -m "Release 2.0.0" origin/main
      git push origin v2.0.0
      ```
- [ ] Watch the CI run for the tag: release guard passes ("tag is on
      main"), attestation created, release created.

## After the release

- [ ] GitHub release `v2.0.0`: `ddate.phar` attached, marked "Latest",
      target `main`.
- [ ] Verify the PHAR:
      `gh attestation verify ddate.phar --repo dweichert/Ddate --source-ref refs/tags/v2.0.0`
- [ ] PHAR runs on PHP 8.6 and refuses older PHP versions with a clear
      message (Box requirement checker).
- [ ] Packagist: `v2.0.0` points to the tagged commit and requires PHP 8.6.
      Versions are immutable on Packagist: a wrong release can only be
      fixed with a new version number.
- [ ] Composer picks the right version for `"^1.1 || ^2.0"`: 2.0.0 on
      PHP 8.6, 1.1.x on PHP 8.5.
- [ ] Edit the release notes: modernised for PHP 8.6, same API as 1.1,
      upgrade = PHP 8.6 only, 1.1 supported until 31 December 2029, API
      changes planned for 3.0; output changes compared with 1.1.4 (bug
      fixes, released in 1.1.6): German `%E` for days 20 and 21, holyday names
      containing "and", `%A` description, `-de`.
- [ ] Release v1.1.7 (checklist on `dev/release-1.1.7`).
- [ ] Issues: close #58 (refactor tests) if done in 2.0; #59 (`@api`,
      `@internal`) stays open for 3.0 (label or milestone).
