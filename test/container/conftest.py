import pytest


def skip_if_php_at_least_86(host, reason):
    version = host.run('php -r "echo PHP_MAJOR_VERSION, \'.\', PHP_MINOR_VERSION;"').stdout
    if [int(part) for part in version.split('.')] >= [8, 6]:
        pytest.skip(reason + ' on PHP ' + version)
