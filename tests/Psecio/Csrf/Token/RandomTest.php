<?php

namespace Psecio\Csrf\Token;

class RandomTest extends \PHPUnit_Framework_TestCase
{
	private $token;

	public function setUp()
	{
		$this->token = new \Psecio\Csrf\Token\Random();
	}

	/**
	 * Validate that a good sha256 hash is generated
	 * @covers \Psecio\Csrf\Token\Random::generate
	 */
	public function testGenerateSha256Valid()
	{
		$options = array(
			'method' => 'sha256'
		);
		$result = $this->token->generate($options);
		$this->assertTrue(strlen($result) == 64);
	}
}