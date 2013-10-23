<?php

namespace Psecio\Csrf\Token;

class Random extends \Psecio\Csrf\Token
{
	/**
	 * Generate the token based off the given options
	 * 
	 * @param array $options Hashing options
	 * @return string Generated token
	 */
	public function generate(array $options = array())
	{
		$token = null;

		if (isset($options['method'])) {
			$function = 'generate'.ucwords(strtolower($options['method']));
			if (method_exists($this, $function)) {
				$token = $this->$function($options);
			} else {
				throw new \InvalidArgumentException('Invalid method '.$options['method']);
			}
		} else {
			$token = (extension_loaded('openssl'))
				? $this->generateOpenSsl($options) : $this->generateBcrypt($options);
		}
		return $token;
	}

	/**
	 * Generate the hash using OpenSSL functionality
	 * 
	 * @param array $options Options for hashing (bytes)
	 * @return string Generated token
	 */
	public function generateOpenssl(array $options)
	{
		$bytes = (isset($options['bytes'])) ? $options['bytes'] : 16;
		$token = bin2hex(openssl_random_pseudo_bytes($bytes));
		return $token;
	}

	/**
	 * Generate the hash as a bcrypt hash
	 * 
	 * @param array $options Options for hashing (cost)
	 * @return string Generated token
	 */
	public function generateBcrypt(array $options)
	{
		$cost = (isset($options['cost']) && is_numeric($options['cost'])) 
			? $options['cost'] : 10;
		$hash = '$2y$'.$cost.'$'.hash('sha256', mt_rand(0,265));
		$rand = hash('sha256', mt_rand(0,265));

		$token = crypt($rand, $hash);
		return $token;
	}

	/**
	 * Generate hash as a sha256 hash
	 * 
	 * @param array $options Options for hashing
	 * @return string Generated token
	 */
	public function generateSha256(array $options)
	{
		$token = hash('sha256', mt_rand(0,265));
		return $token;
	}
}