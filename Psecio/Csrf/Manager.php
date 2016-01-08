<?php
namespace Psecio\Csrf;
use Psecio\Csrf\Storage\Session;
use Psecio\Csrf\Storage\Cookie;

class Manager
{
    /**
     * Set of storage methods
     * @var array
     */
    protected $storage = [];

    /**
     * Hashing method for the key generation
     */
    const HASH_METHOD = 'SHA256';

    /**
     * Constants for generator types
     */
    const GENERATE_OPENSSL = 'openssl';
    const GENERATE_CSPRNG = 'csprng';

    /**
     * Init the object and optionally use double-submit tokens and
     * 	define a custom storage method
     *
     * @param boolean $doubleSubmit Use double submit (cookie) tokens
     * @param \Psecio\Storage $storage Storage method instance
     */
    public function __construct($doubleSubmit = false, Storage $storage = null)
    {
        if ($storage === null) {
            $storage = new Session();
        }
        $this->addStorage($storage);

        if ($doubleSubmit === true) {
            $this->addStorage(new Cookie());
        }
    }

    /**
     * Add a new storage method
     *
     * @param \Psecio\Storage $storage Storage method
     */
    public function addStorage(Storage $storage)
    {
        $this->storage[get_class($storage)] = $storage;
    }

    /**
     * Set the current storage methods
     *
     * @param array $storage Set of storage methods
     */
    public function setStorage(array $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Get the current storage methods
     *
     * @return array Set of Storage methods (instances)
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Generate the token and save it to the current methods
     *
     * @param string $type Type of generator to use (openssl, csprng)
     * @param array $config Custom configuration options to pass to the generator
     * @return string Full token (key and random token combined)
     */
    public function generate($type = self::GENERATE_OPENSSL, array $config = [])
    {
        // Generate the code with the provided method
        $typeNs = '\\Psecio\\Csrf\\Token\\'.ucwords(strtolower($type));
        if (!class_exists($typeNs)) {
            throw new \Exception('Cannot generate token with generator type: '.$type);
        }

        $generator = new $typeNs();
        $token = $generator->generate($config);

        // Generate a random "key" to push it into the storage
        $key = $this->generateKey();

        $this->save($key, $token);
        return $key.$token;
    }

    /**
     * Save the key/token combination to the current storage methods
     *
     * @param string $key Key to use for storage
     * @param string $token Token value
     */
    public function save($key, $token)
    {
        foreach ($this->getStorage() as $type => $storage) {
            $storage->save($key, $token);
        }
    }

    /**
     * Generate teh randomized key value
     *
     * @param integer $length Length of the key to generate
     * @return string Hash of randomly generated key
     */
    public function generateKey($length = 10)
    {
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= ord(mt_rand(0,126));
        }
        return hash(self::HASH_METHOD, $string);
    }

    /**
     * Verify the token provided
     *
     * @param string $token Token to validate
     * @return boolean Result of verification for current storage methods
     */
    public function verify($token)
    {
        // first, split off our hash
        $key = substr($token, 0, 64);
        $hash = substr($token, 64);

        // Now look through our storage and check that we have matches in all
        $result = true;
        foreach ($this->getStorage() as $type => $storage) {
            $check = $storage->get($key);
            if ($check === null && $result === true) {
                $result = false;
            } else {
                $storage->delete($key);
            }
        }

        return $result;
    }
}
