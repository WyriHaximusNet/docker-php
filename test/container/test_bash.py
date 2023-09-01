import pytest

@pytest.mark.php_zts
@pytest.mark.php_nts
def test_bash_true_results_in_0(host):
    output = host.run('bash -c "true"')
    assert output.rc == 0

@pytest.mark.php_zts
@pytest.mark.php_nts
def test_bash_true_results_in_0(host):
    output = host.run('bash -c "false"')
    assert output.rc > 0
