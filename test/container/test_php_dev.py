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

@pytest.mark.php_dev
def test_php_meminfo_is_enabled(host):
    output = host.run('if [ $(php -v | grep 7.4 | wc -l) != 0 ] ; then php -r "echo(\'meminfo\');"; else php -r "exit(function_exists(\'meminfo_dump\') ? 0 : 255);"; fi')
    assert output.rc == 0

@pytest.mark.php_no_dev
def test_php_meminfo_is_not_enabled(host):
    output = host.run('php -r "exit(function_exists(\'meminfo_dump\') ? 0 : 255);"')
    assert output.rc == 255

@pytest.mark.php_dev
def test_php_ext_meminfo_is_functional(host):
    output = host.run('if [ $(php -v | grep 7.4 | wc -l) != 0 ] ; then php -r "echo(\'meminfo\');"; else php /tests/container/functional/meminfo.php; fi')
    assert output.stdout == 'meminfo'
    assert output.rc == 0
