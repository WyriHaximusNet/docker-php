import pytest

@pytest.mark.php_zts
@pytest.mark.php_nts
def test_in_docker_file_exists(host):
    output = host.run('php -r "exit(file_exists(\'/.you-are-in-a-wyrihaximus.net-php-docker-image\') ? 0 : 255);"')
    assert output.rc == 0

@pytest.mark.php_dev
def test_in_docker_file_exists(host):
    output = host.run('php -r "exit(file_exists(\'/.you-are-in-a-wyrihaximus.net-php-docker-image-dev\') ? 0 : 255);"')
    assert output.rc == 0

@pytest.mark.php_dev
def test_make_works(host):
    output = host.run('make')
    assert output.stdout == ''
    assert u'No targets specified and no makefile found.' in output.stderr
    assert output.rc == 2
