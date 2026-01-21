import pytest

@pytest.mark.php_cli
@pytest.mark.php_nts
def test_configuration_is_effective(host):
    config = get_config(host)

    assert u'memory_limit => -1 => -1' in config
    assert u'opcache.enable_cli => On => On' in config
    assert u'opcache.jit => 1255 => 1255' in config
    assert u'opcache.jit_buffer_size => 128M => 128M' in config

@pytest.mark.php_dev
@pytest.mark.php_zts
def test_configuration_is_effective(host):
    config = get_config(host)

    assert u'memory_limit => -1 => -1' in config
    assert u'opcache.enable_cli => On => On' in config
    assert u'opcache.jit => disable => disable' in config
    assert u'opcache.jit_buffer_size => 128M => 128M' in config

def get_config(host):
    return host.run('php -i').stdout
