import pytest

@pytest.mark.php_dev
def test_configuration_is_present(host):
    assert host.file('/usr/local/etc/php/conf.d/zzz_xdebug.ini').exists is True
    assert host.file('/usr/local/etc/php/conf.d/zzz_dev.ini').exists is True

@pytest.mark.php_no_dev
def test_configuration_is_not_present(host):
    assert host.file('/usr/local/etc/php/conf.d/zzz_xdebug.ini').exists is False
    assert host.file('/usr/local/etc/php/conf.d/zzz_dev.ini').exists is False

@pytest.mark.php_dev
def composer_is_functional(host):
    output = host.run('composer about')
    assert output.strerr == ''
    assert u'version 2' in output.stdout
    assert output.rc == 0

@pytest.mark.php_dev
def test_ffs_is_loaded(host):
    assert 'FFI' not in host.run('php -m').stdout

@pytest.mark.php_dev
def test_grpc_is_loaded(host):
    assert 'grpc' not in host.run('php -m').stdout

@pytest.mark.php_dev
def test_opentelemetry_is_loaded(host):
    assert 'opentelemetry' not in host.run('php -m').stdout
