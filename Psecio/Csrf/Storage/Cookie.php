<?php
namespace Psecio\Csrf\Storage;

class Cookie extends \Psecio\Csrf\Storage
{
    protected $namespace = 'csrf-cookie';

    public function save($key, $code)
    {
        $timeout = ($this->getConfig('timeout') === null) ? time() + 60 : $this->getConfig('timeout');
        $secure = (!empty($_SERVER['HTTPS']));

        setcookie($key, $code, $timeout, '/', $_SERVER['SERVER_NAME'], $secure, true);
    }

    public function get($key)
    {
        return (isset($_COOKIE[$key])) ? $_COOKIE[$key] : null;
    }
    public function delete($key)
    {
        setcookie($key, '', time()-1000);
    }
}
