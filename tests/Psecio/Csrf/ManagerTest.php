<?php

namespace Psecio\Csrf;

session_start();

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that a Session storage type is created when the Manager
     * 	class is initialized
     */
    public function testSetupDefaultStorage()
    {
        $manager = new Manager();
        $storage = $manager->getStorage();

        $this->assertInstanceOf('Psecio\Csrf\Storage\Session', array_pop($storage));
    }

    /**
     * Test that setting the storage overrides the default
     */
    public function testSetStorageAll()
    {
        $manager = new Manager();
        $manager->setStorage([
            new \Psecio\Csrf\Storage\Cookie()
        ]);
        $storage = $manager->getStorage();

        $this->assertEquals(1, count($storage));
        $this->assertInstanceOf('Psecio\Csrf\Storage\Cookie', array_pop($storage));
    }

    /**
     * Test the generation of a valid token and that it's correctly
     * 	stored on the session (default)
     */
    public function testGenerateValidToken()
    {
        $manager = new Manager();
        $token = $manager->generate();
        list($key, $hash) = $manager->splitToken($token);

        // Be sure the token is in the storage
        $storage = $manager->getStorage();
        $sessionStorage = array_pop($storage);

        $this->assertEquals($token, $sessionStorage->get($key));
    }

    /**
     * Test that an exception is thrown when the type of generator is invalid
     *
     * @expectedException \InvalidArgumentException
     */
    public function testGenerateTokenInvalidType()
    {
        $manager = new Manager();
        $token = $manager->generate('badtype');
    }
}
