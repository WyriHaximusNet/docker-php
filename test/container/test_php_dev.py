import pytest

@pytest.mark.php_dev
def test_configuration_is_present(host):
    assert host.file('/usr/local/etc/php/conf.d/zzz_xdebug.ini').exists is True
    assert host.file('/usr/local/etc/php/conf.d/zzz_dev.ini').exists is True

@pytest.mark.php_dev
def test_configuration_is_effective(host):
    configuration = host.run('php -i').stdout
    
    assert u'expose_php => On => On' in configuration

@pytest.mark.php_no_dev
def test_configuration_is_not_present(host):
    assert host.file('/usr/local/etc/php/conf.d/zzz_xdebug.ini').exists is False
    assert host.file('/usr/local/etc/php/conf.d/zzz_dev.ini').exists is False
    
@pytest.mark.php_no_dev
def test_configuration_is_not_effective(host):
    configuration = host.run('php -i').stdout
    
    assert u'expose_php => Off => Off' in configuration
