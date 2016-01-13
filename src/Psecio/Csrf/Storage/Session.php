<?php
namespace Psecio\Csrf\Storage;

class Session extends \Psecio\Csrf\Storage
{
    protected $namespace = 'csrf-session';

    public function __construct(array $config = [], $namespace = null)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        parent::__construct($config, $namespace);
    }

    public function save($key, $code)
    {
        $_SESSION[$this->namespace][$key] = $key.$code;
    }

    public function get($key)
    {
        return (isset($_SESSION[$this->namespace][$key]))
            ? $_SESSION[$this->namespace][$key] : null;
    }

    public function delete($key)
    {
        unset($_SESSION[$this->namespace][$key]);
    }
}
