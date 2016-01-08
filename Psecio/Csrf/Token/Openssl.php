<?php
namespace Psecio\Csrf\Token;

class Openssl implements \Psecio\Csrf\TokenGenerator
{
    public function generate(array $config = [])
    {
        // Be sure we have Openssl
        if (!function_exists('openssl_random_pseudo_bytes')) {
            throw new \Exception('OpenSSL support is not installed!');
        }

        $bytes = (isset($config['bytes'])) ? $config['bytes'] : 32;
		$token = bin2hex(openssl_random_pseudo_bytes($bytes));
		return $token;
    }
}
