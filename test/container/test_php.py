import pytest

@pytest.mark.php_nts
@pytest.mark.php_zts
def test_php_pcntl_is_enabled(host):
    output = host.run('php -r "exit(function_exists(\'pcntl_signal\') ? 0 : 255);"')
    assert output.rc == 0

    output = host.run('php -r "exit(function_exists(\'pcntl_async_signals\') ? 0 : 255);"')
    assert output.rc == 0

@pytest.mark.php_nts
@pytest.mark.php_zts
def test_php_pcntl_is_enabled(host):
    output = host.run('php -r "exit(function_exists(\'uv_loop_new\') ? 0 : 255);"')
    assert output.rc == 0

    output = host.run('php -r "exit(function_exists(\'uv_timer_init\') ? 0 : 255);"')
    assert output.rc == 0

@pytest.mark.php_zts
def test_php_pcntl_is_enabled(host):
    output = host.run('php -r "exit(class_exists(\'parallel\Runtime\') ? 0 : 255);"')
    assert output.rc == 0

    output = host.run('php -r "exit(class_exists(\'parallel\Future\') ? 0 : 255);"')
    assert output.rc == 0

@pytest.mark.php_nts
def test_php_pcntl_is_enabled(host):
    output = host.run('php -r "exit(class_exists(\'parallel\Runtime\') ? 0 : 255);"')
    assert output.rc == 255

    output = host.run('php -r "exit(class_exists(\'parallel\Future\') ? 0 : 255);"')
    assert output.rc == 255
