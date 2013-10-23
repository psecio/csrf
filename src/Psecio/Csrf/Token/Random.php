<?php

namespace Psecio\Csrf\Token;

class Random extends \Psecio\Csrf\Token
{
	public function generate(array $options = array())
	{
		$bytes = (isset($options['bytes'])) ? $options['bytes'] : 16;
		$token = bin2hex(openssl_random_pseudo_bytes($bytes));
		return $token;
	}
}