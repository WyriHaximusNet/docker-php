import pytest

@pytest.mark.php_app
def test_php_runs_as_app(host):
    output = host.run('php -r "echo getmygid();"')
    assert output.stdout == '1000'

    output = host.run('php -r "echo getmyuid();"')
    assert output.stdout == '1000'

@pytest.mark.php_root
def test_php_runs_as_root(host):
    output = host.run('php -r "echo getmygid();"')
    assert output.stdout == '0'

    output = host.run('php -r "echo getmyuid();"')
    assert output.stdout == '0'

@pytest.mark.php_nts
@pytest.mark.php_zts
def test_php_pcntl_is_enabled(host):
    output = host.run('php -r "exit(function_exists(\'pcntl_signal\') ? 0 : 255);"')
    assert output.rc == 0

    output = host.run('php -r "exit(function_exists(\'pcntl_async_signals\') ? 0 : 255);"')
    assert output.rc == 0

@pytest.mark.php_nts
@pytest.mark.php_zts
def test_php_ext_uv_is_enabled(host):
    output = host.run('php -r "exit(function_exists(\'uv_loop_new\') ? 0 : 255);"')
    assert output.rc == 0

    output = host.run('php -r "exit(function_exists(\'uv_timer_init\') ? 0 : 255);"')
    assert output.rc == 0

@pytest.mark.php_zts
def test_php_ext_parallel_is_enabled(host):
    output = host.run('php -r "exit(class_exists(\'parallel\\Runtime\') || PHP_VERSION_ID > 80000 ? 0 : 255);"')
    assert output.rc == 0

    output = host.run('php -r "exit(class_exists(\'parallel\\Future\') || PHP_VERSION_ID > 80000 ? 0 : 255);"')
    assert output.rc == 0

@pytest.mark.php_nts
def test_php_ext_parallel_is_not_enabled(host):
    output = host.run('php -r "exit(class_exists(\'parallel\\Runtime\') ? 0 : 255);"')
    assert output.rc == 255

    output = host.run('php -r "exit(class_exists(\'parallel\\Future\') ? 0 : 255);"')
    assert output.rc == 255

@pytest.mark.php_zts
def test_php_ext_parallel_is_functional(host):
    output = host.run('php /tests/container/functional/parallel.php')
    assert output.rc == 33

    output = host.run('php /tests/container/functional/parallel-multi.php')
    assert output.rc == 65

@pytest.mark.php_zts
def test_php_ext_uv_is_functional(host):
    output = host.run('php /tests/container/functional/uv-timer.php')
    assert output.stdout == '0123finished'
    assert output.rc == 0

@pytest.mark.php_not_slim
def test_php_ext_vips_is_enabled(host):
    output = host.run('php -r "exit(function_exists(\'vips_version\') ? 0 : 255);"')
    assert output.rc == 0