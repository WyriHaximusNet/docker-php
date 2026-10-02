import pytest

# 100 MiB stack (102400 KiB) allows ~25 MiB exec argument space on Linux (stack / 4).
STACK_SIZE_KB = 102400


@pytest.mark.php_zts
@pytest.mark.php_nts
def test_stack_limit_allows_large_exec_arguments(host):
    output = host.run('docker-php-entrypoint-with-stack sh -c "ulimit -s"')
    assert output.rc == 0
    assert int(output.stdout.strip()) >= STACK_SIZE_KB
