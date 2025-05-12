import pytest

@pytest.mark.php_cli
def test_configuration_is_effective(host):
    config = get_config(host)

    assert u'memory_limit => -1 => -1' in config
    assert u'opcache.enable_cli => On => On' in config

def get_config(host):
    return host.run('php -i').stdout
