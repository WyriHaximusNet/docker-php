import pytest

@pytest.mark.php_zts
@pytest.mark.php_nts
def test_bcmath_is_loaded(host):
    assert 'bcmath' in host.run('php -m').stdout

@pytest.mark.php_not_slim
def test_gd_is_loaded(host):
    assert 'gd' in host.run('php -m').stdout

@pytest.mark.php_zts
@pytest.mark.php_nts
def test_gmp_is_loaded(host):
    assert 'gmp' in host.run('php -m').stdout

@pytest.mark.php_zts
@pytest.mark.php_nts
def test_iconv_is_loaded(host):
    assert 'iconv' in host.run('php -m').stdout

@pytest.mark.php_zts
@pytest.mark.php_nts
def test_iconv_is_loaded(host):
    assert 'intl' in host.run('php -m').stdout

@pytest.mark.php_zts
def test_parallel_is_loaded(host):
    assert 'parallel' in host.run('php -m').stdout

@pytest.mark.php_nts
def test_parallel_is_not_loaded(host):
    assert 'parallel' not in host.run('php -m').stdout

@pytest.mark.php_zts
@pytest.mark.php_nts
def test_pcntl_is_loaded(host):
    assert 'pcntl' in host.run('php -m').stdout

@pytest.mark.php_zts
@pytest.mark.php_nts
def test_pgsql_is_loaded(host):
    assert 'pdo' in host.run('php -m').stdout
    assert 'pgsql' in host.run('php -m').stdout
    assert 'pdo_pgsql' in host.run('php -m').stdout

@pytest.mark.php_zts
@pytest.mark.php_nts
def test_uv_is_loaded(host):
    assert 'uv' in host.run('php -m').stdout

@pytest.mark.php_not_slim_amd64
def test_vips_is_loaded(host):
    assert 'vips' in host.run('php -m').stdout

@pytest.mark.php_dev
def test_xdebug_is_loaded(host):
    assert 'Xdebug' in host.run('php -m').stdout

@pytest.mark.php_no_dev
def test_xdebug_is_not_loaded(host):
    assert 'Xdebug' not in host.run('php -m').stdout

@pytest.mark.php_zts
@pytest.mark.php_nts
def test_zip_is_loaded(host):
    assert 'zip' in host.run('php -m').stdout
