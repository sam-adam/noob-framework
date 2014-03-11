<?php

namespace Noob\Inject\Binding\Metadata;

use Noob\Core\Enforce;
use Noob\Inject\Module\BaseModule;

class BindingMetaCollection extends \ArrayObject implements BindingMetaInterface {
    private $name;

    /**
     * Constructor
     *
     * @param string $name The binding's name
     */
    public function __construct($name) {
        Enforce::ArgumentNotNull($name, 'name');

        $this->name = $name;
    }

    /**
     * Gets the binding's name
     *
     * @return string
     */
    function getBindingName() {
        return $this->name;
    }

    /**
     * Sets the binding's name
     *
     * @param string $name
     */
    function setBindingName($name) {
        Enforce::ArgumentNotNullOrEmpty($name, 'name');

        $this->name = $name;
    }

    /**
     * Check if a metadata exists
     *
     * @param string $key
     * @return bool
     */
    function has($key) {
        Enforce::ArgumentNotNullOrEmpty($key, 'key');

        return $this->offsetExists($key);
    }

    /**
     * Gets the metadata for the provided key
     * Returns the default value if not found
     *
     * @param string $key
     * @param null $default
     * @return mixed
     */
    function get($key, $default = null) {
        Enforce::ArgumentNotNullOrEmpty($key, 'key');

        return $this->has($key)
            ? $this->offsetGet($key)
            : $default;
    }

    /**
     * Sets the metadata for the provided key
     *
     * @param string $key
     * @param mixed $value
     */
    function set($key, $value) {
        Enforce::ArgumentNotNullOrEmpty($key, 'key');
        Enforce::ArgumentNotNull($value, 'value');

        $this[$key] = $value;
    }
}