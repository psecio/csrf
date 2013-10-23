<?php

namespace Psecio\Csrf;

class Token
{
	public function generate($type, array $options = array())
	{
		$tokenClass = "\\Psecio\\Csrf\\Token\\".ucwords(strtolower($type));
		if (class_exists($tokenClass) === true) {
			$token = new $tokenClass();
			return $token->generate($options);
		}
		return false;
	}
}