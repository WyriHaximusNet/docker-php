import pytest


def is_php_pre_release(host):
    """Match Dockerfile pre-release gates (alpha/beta/RC), not a fixed minor version."""
    php_v = host.run('php -v').stdout.lower()
    if 'alpha' in php_v or 'beta' in php_v:
        return True
    version = host.run('php -r "echo PHP_VERSION;"').stdout.strip().lower()
    return 'alpha' in version or 'beta' in version or 'rc' in version


def skip_if_php_pre_release(host, reason):
    if is_php_pre_release(host):
        version = host.run('php -r "echo PHP_VERSION;"').stdout.strip()
        pytest.skip(reason + ' on PHP ' + version)
