import pytest

@pytest.mark.php_dev
def test_strace_is_functional(host):
    output = host.run('strace')
    assert output.stdout == ''
    assert u'strace' in output.stderr
    assert output.rc == 1
