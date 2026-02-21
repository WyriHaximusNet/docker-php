import pytest

@pytest.mark.php_dev
def test_terraform(host):
    assert 'Terraform v' in host.run('terraform version').stdout
    assert 'on linux_' in host.run('terraform version').stdout
