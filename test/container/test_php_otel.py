import pytest

@pytest.mark.php_nts
@pytest.mark.php_zts
def test_ffs_is_loaded(host):
    assert 'FFI' in host.run('php -m').stdout

@pytest.mark.php_nts
@pytest.mark.php_zts
def test_grpc_is_loaded(host):
    assert 'grpc' in host.run('php -m').stdout

@pytest.mark.php_nts
@pytest.mark.php_zts
def test_opentelemetry_is_loaded(host):
    assert 'opentelemetry' in host.run('php -m').stdout
