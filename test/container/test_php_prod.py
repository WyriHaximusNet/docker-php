import pytest

@pytest.mark.php_dev
def test_configuration_is_present(host):
    assert host.file('/usr/local/etc/php/conf.d/zzz_prod.ini').exists is False

@pytest.mark.php_no_dev
def test_configuration_is_not_present(host):
    assert host.file('/usr/local/etc/php/conf.d/zzz_prod.ini').exists is True

@pytest.mark.php_no_dev
def test_configuration_has_assertions_disabled(host):
    config = get_config(host)

    assert u'zend.assertions => -1 => -1' in config

def get_config(host):
    return host.run('php -i').stdout
