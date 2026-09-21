import pytest

from conftest import skip_if_php_at_least_86

@pytest.mark.php_nts
@pytest.mark.php_zts
def test_ffs_is_loaded(host):
    assert 'FFI' in host.run('php -m').stdout

@pytest.mark.php_nts
@pytest.mark.php_zts
def test_opentelemetry_is_loaded(host):
    skip_if_php_at_least_86(host, 'ext-opentelemetry')
    assert 'opentelemetry' in host.run('php -m').stdout

@pytest.mark.php_nts
@pytest.mark.php_zts
def test_protobuf_is_loaded(host):
    skip_if_php_at_least_86(host, 'ext-protobuf')
    assert 'protobuf' in host.run('php -m').stdout
