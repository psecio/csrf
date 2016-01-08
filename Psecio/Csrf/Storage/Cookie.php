<?php
namespace Psecio\Csrf\Storage;

class Cookie extends \Psecio\Csrf\Storage
{
    protected $namespace = 'csrf-cookie';

    public function save($key, $code)
    {
        setcookie($key, $code);
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
