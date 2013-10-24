<?php

namespace Psecio\Csrf;

class Token
{
	/**
	 * Given a type and possible options, generate the token
	 * 
	 * @param string $type Method for generating the token
	 * @param array $options Additional options for the request
	 * @throws \InvalidArgumentException If type is invalid/not supported
	 * @return mixed Either false on failure or the hash on success
	 */
	public function generate($type, array $options = array())
	{
		$tokenClass = "\\Psecio\\Csrf\\Token\\".ucwords(strtolower($type));
		if (class_exists($tokenClass) === true) {
			$token = new $tokenClass();
			return $token->generate($options);
		} else {
			throw new \InvalidArgumentException('Invalid token type '.$type);
		}
		return false;
	}
}