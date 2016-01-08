<?php

namespace Psecio\Csrf;

abstract class Storage
{
    /**
     * Namespace for the storage
     * 	(to keep it out of global scope)
     * @var string
     */
    protected $namespace;

    /**
     * Configuration options
     * @var array
     */
    protected $config = [];

    /**
     * Init the object and set up the configuration and namespace
     *
     * @param array $config Configuration options
     * @param string $namespace Namespace to use in storage
     */
    public function __construct(array $config = [], $namespace = null)
    {
        if ($namespace !== null) {
            $this->setNamespace($namespace);
        }
        if (!empty($config)) {
            $this->setConfig($config);
        }
    }

    /**
     * Set the current configuration
     *
     * @param array $config Configuration options
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get the configuration options, optionally provide a key to
     * 	locate a certain option. If no key provided, all options returned
     *
     * @param string $key Key to locate [optional]
     * @return mixed Either all configuration options, one option or null
     */
    public function getConfig($key = null)
    {
        if ($key !== null) {
            return (isset($this->config[$key])) ? $this->config[$key] : null;
        } else {
            return $this->config;
        }
    }

    /**
     * Save the code with the provided key
     *
     * @param string $key Key to use as save index
     * @param string $code Code to save
     */
    public abstract function save($key, $code);

    /**
     * Get a code for the provided key
     *
     * @param string $key Key to locate
     * @return string Token matching the key value
     */
    public abstract function get($key);

    /**
     * Delete the value provided by the key
     *
     * @param string $key Key to delete
     */
    public abstract function delete($key);
}
