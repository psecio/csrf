<?php

namespace Psecio\Csrf\Token;

class Csprng implements \Psecio\Csrf\TokenGenerator
{
    public function generate(array $config = [])
    {
        if (version_compare(PHP_VERSION, '7.0.0') < 0) {
            throw new \InvalidArgumentException('Cannot use CSPRNG generation on PHP <7.0.0');
        }
        $bytes = (isset($config['bytes'])) ? $config['bytes'] : 32;
        return bin2hex(random_bytes($bytes));
    }
}
