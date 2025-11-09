import pytest

@pytest.mark.php_zts
@pytest.mark.php_nts
def test_pie_works(host):
    output = host.run('pie')
    assert u'🥧 PHP Installer for Extensions (PIE) 1.' in output.stdout
    assert output.stderr == ''
    assert output.rc == 0
