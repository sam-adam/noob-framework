<?php

namespace Noob\Http\Request;

class ParameterCollection extends \ArrayObject {
    /**
     * Constructor
     *
     * Only array is accepted
     *
     * @param array $items
     */
    public function __construct(array $items = []) {
        parent::__construct($items, \ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Return the parameters
     *
     * @return array an Array of parameters
     */
    public function all() {
        return $this;
    }

    /**
     * Get item by key
     *
     * Return null if item is not found
     *
     * @param mixed $key
     * @return mixed
     */
    public function offsetGet($key) {
        return isset($this[$key])
            ? parent::offsetGet($key)
            : null;
    }

    /**
     * Get item by key
     *
     * Return the given default value if not found
     *
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default) {
        return $this->offsetGet($key) ?: $default;
    }

    /**
     * Add an item to the collection
     *
     * @param $key
     * @param $value
     * @return ParameterCollection
     */
    public function add($key, $value) {
        $this[$key] = $value;

        return $this;
    }

    /**
     * Return the parameter keys
     *
     * @return array an Array of parameter keys
     */
    public function keys() {
        return array_keys($this);
    }
}