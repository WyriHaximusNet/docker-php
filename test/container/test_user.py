import pytest

@pytest.mark.php_app
def test_user_app(host):
    userName = 'app'
    groupName = 'app'
    homeDir = '/home/app'

    usr = host.user(userName)
    assert userName in usr.name
    assert groupName in usr.group
    assert homeDir in usr.home

@pytest.mark.php_root
def test_user_root(host):
    userName = 'root'
    groupName = 'root'
    homeDir = '/root'

    usr = host.user(userName)
    assert userName in usr.name
    assert groupName in usr.group
    assert homeDir in usr.home
