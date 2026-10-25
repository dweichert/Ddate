# TODO: release v1.1.6 together with v2.0.0

Checklist for the 1.1.x release that accompanies 2.0.0 (planned for the
PHP 8.6 release, 19 November 2026). **Delete this file before merging this
branch into `1.1`.**

Release 2.0.0 first (see `TODO.md` on branch `dev/release-2.0.0`), then this
release: the README here calls 2.0 the current version and links to
`docs/release-plan.md` on `main`.

## Before the release day

- [x] German fixes (ordinals, holyday names, help text, CLI): released in
      v1.1.5 (#72). Supersedes `4e1d175` from the previous `main`, which
      introduced a broken `%Feiert`.
- [ ] Review the updated holyday data from the previous `main` (`2fe4715`);
      if it fixes data, add it to `1.1` (for 1.1.6) and `main` with tests.
- [ ] Run the test suite on all PHP versions: `.docker/run-tests.sh`
- [ ] Private vulnerability reporting is enabled on GitHub (SECURITY.md on
      `main` links to it): `gh api repos/dweichert/Ddate/private-vulnerability-reporting`
      shows `"enabled":true`.

## On the release day

- [ ] v2.0.0 is released: GitHub release marked "Latest", Packagist lists
      `v2.0.0`.
- [x] Release guard and `--latest=false` on `1.1` (#68),
      `docs/release-plan.md` and `SECURITY.md` on `main` (#69); the README
      links to the release plan.
- [ ] README: "last updated" date is the actual release day. Compute it with
      `php bin/ddate '%A, %e of %B, %Y' DDMMYYYY`
      (19 November 2026 = Pungenday, 31st of The Aftermath, 3192).
- [ ] README: section "Version 1.1 and 2.0" is still correct (end of life
      31 December 2029, PHP 8.6 for 2.0, `"^1.1 || ^2.0"`), the release
      table lists 2.0.x as current version.
- [ ] Delete `TODO.md` (`git rm TODO.md`), commit, push.
- [ ] Pull request into **`1.1`** (not `main`), CI green, merge with
      "Create a merge commit".
- [ ] Tag on `1.1` and push the tag. Do **not** create the release in the
      GitHub UI first.
      ```sh
      git fetch origin
      git log --oneline -1 origin/1.1     # the merge of this branch
      git tag -a v1.1.6 -m "Release 1.1.6" origin/1.1
      git push origin v1.1.6
      ```
- [ ] Watch the CI run for the tag: release guard passes ("tag is on 1.1"),
      attestation created, release created.

## After the release

- [ ] GitHub release `v1.1.6`: `ddate.phar` attached, **not** marked
      "Latest"; `v2.0.0` is still "Latest".
- [ ] Verify the PHAR:
      `gh attestation verify ddate.phar --repo dweichert/Ddate --source-ref refs/tags/v1.1.6`
- [ ] Packagist: `v1.1.6` points to the tagged commit and requires
      `"php": ">=5.3.0"`. Versions are immutable on Packagist: a wrong
      release can only be fixed with a new version number.
- [ ] Composer picks the right version for `"^1.1 || ^2.0"`: 1.1.6 on
      PHP 8.5, 2.0.0 on PHP 8.6.
- [ ] Edit the release notes: end of life 31 December 2029, pointer to 2.0,
      `"^1.1 || ^2.0"`; output changes from the holyday data review, if any;
      changelog link `v1.1.5...v1.1.6`.
