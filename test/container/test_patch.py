import pytest

@pytest.mark.php_dev
def test_patch_is_available(host):
    output = host.run('patch --version')
    assert output.rc == 0
    assert u'patch' in output.stdout.lower()
    assert output.stderr == ''

@pytest.mark.php_dev
def test_patch_can_apply(host):
    host.run('mkdir -p /tmp/patch-test')
    host.run('printf "hello\\n" > /tmp/patch-test/file.txt')

    output = host.run(
        'patch -p1 -d /tmp/patch-test < /tests/container/functional/patch-fixture.patch'
    )

    assert output.rc == 0
    assert output.stderr == ''
    assert host.file('/tmp/patch-test/file.txt').content_string == u'hello world\n'
