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

}
