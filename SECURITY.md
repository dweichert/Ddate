# Security policy

## Supported versions

| Version | PHP           | Security fixes                                              |
|---------|---------------|-------------------------------------------------------------|
| 2.0.x   | 8.6 and newer | yes                                                         |
| 1.1.x   | 5.3 to 8.5    | yes, until 31 December 2029 (end of PHP 8.5 security support) |
| < 1.1   | –             | no                                                          |

See the [release plan](docs/release-plan.md) for details.

## Reporting a vulnerability

Please do **not** report security vulnerabilities in public issues or pull
requests. Report them privately through GitHub instead:

<https://github.com/dweichert/Ddate/security/advisories/new>

Please include the affected version, your PHP version, steps to reproduce and
the impact you expect. Ddate is maintained by a single volunteer, so responses
are on a best-effort basis.

Confirmed vulnerabilities are fixed in a new patch release of every supported
version and published as a GitHub security advisory. Reviewed advisories
end up in the GitHub Advisory Database, which `composer audit` uses.

## Verifying downloads

PHAR files from release 1.1.4 on are built by GitHub Actions and signed with a
GitHub artifact attestation. See the [README](README.md) for how to verify
them.
