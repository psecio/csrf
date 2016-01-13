<?php
namespace Psecio\Csrf;

interface TokenGenerator
{
    /**
     * Token generator method
     *
     * @param array $config Configuration options
     */
    public function generate(array $config = []);
}
