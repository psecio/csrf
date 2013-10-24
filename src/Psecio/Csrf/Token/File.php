<?php

namespace Psecio\Csrf\Token;

class File
{
	/**
	 * Generate the token based off the given options
	 * 	and the contents of a (non-binary) file
	 * 
	 * @param array $options Hashing options
	 * @return string Generated token
	 */
	public function generate(array $options = array())
	{
		if (!isset($options['path']) || !is_file($options['path'])) {
			throw new \InvalidArgumentException('Invalid path to entropy file');
		}

		// use the filepath given to generate the token
		$contents = file_get_contents($options['path']);

		// randomize the data used for the hash
		$contents = substr($contents, 0, mt_rand(0, strlen($contents)-1));
		$seed = '';

		for ($i=0; $i<strlen($contents); $i++) {
			$seed .= ord($contents{$i});
		}
		mt_srand($seed);
		return hash('sha256', rand());
	}
}