import pytest

@pytest.mark.php_nts
@pytest.mark.php_zts
def test_wait_for_is_functional(host):
    output = host.run('wait-for google.com:443 -- wait-for hub.docker.com:443 -- echo 1000')
    assert output.stdout == '1000\n'
    assert output.rc == 0
