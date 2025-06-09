import pytest

@pytest.mark.php_dev
def test_ffs_is_loaded(host):
    assert 'FFI' not in host.run('php -m').stdout

@pytest.mark.php_dev
def test_grpc_is_loaded(host):
    assert 'grpc' not in host.run('php -m').stdout

@pytest.mark.php_dev
def test_opentelemetry_is_loaded(host):
    assert 'opentelemetry' not in host.run('php -m').stdout

@pytest.mark.php_no_dev
def test_ffs_is_loaded(host):
    assert 'FFI' in host.run('php -m').stdout

@pytest.mark.php_no_dev
def test_grpc_is_loaded(host):
    assert 'grpc' in host.run('php -m').stdout

@pytest.mark.php_no_dev
def test_opentelemetry_is_loaded(host):
    assert 'opentelemetry' in host.run('php -m').stdout
