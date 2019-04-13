import pytest
import os

@pytest.mark.php_nts
@pytest.mark.php_zts
def test_php_runs_as_app(host):
    output = host.run('php -r "echo getmygid();"')
    assert output.stdout == '1000'

    output = host.run('php -r "echo getmyuid();"')
    assert output.stdout == '1000'

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
    output = host.run('php -r "exit(class_exists(\'parallel\\Runtime\') ? 0 : 255);"')
    assert output.rc == 0

    output = host.run('php -r "exit(class_exists(\'parallel\\Future\') ? 0 : 255);"')
    assert output.rc == 0

@pytest.mark.php_nts
def test_php_ext_parallel_is_not_enabled(host):
    output = host.run('php -r "exit(class_exists(\'parallel\\Runtime\') ? 0 : 255);"')
    assert output.rc == 255

    output = host.run('php -r "exit(class_exists(\'parallel\\Future\') ? 0 : 255);"')
    assert output.rc == 255

@pytest.mark.php_zts
def test_php_ext_parallel_is_functional(host):
    __dir__ = os.path.split(os.path.realpath(__file__))[0];

    output = host.run('php {__dir__}test/container/functional/parallel.php')
    assert output.rc == 33

    output = host.run('php {__dir__}test/container/functional/parallel-multi.php')
    assert output.rc == 65
