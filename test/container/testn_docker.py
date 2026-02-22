import pytest

@pytest.mark.php_dev
def test_docker_is_available(host):
    output = host.run('docker')
    assert u'Docker version ' in output.stdout
    assert output.stderr == ''
    assert output.rc == 0
