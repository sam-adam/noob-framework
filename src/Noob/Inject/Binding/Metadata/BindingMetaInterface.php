<?php

namespace Noob\Inject\Binding\Metadata;

/**
 * BindingMetaInterface
 * @package Noob\Inject\Binding
 */
interface BindingMetaInterface {
    /**
     * Gets the binding's name
     *
     * @return string
     */
    function getBindingName();

    /**
     * Sets the binding's name
     *
     * @param string $name
     */
    function setBindingName($name);

    /**
     * Check if a metadata exists
     *
     * @param string $key
     * @return bool
     */
    function has($key);

    /**
     * Gets the metadata for the provided key
     *
     * @param string $key
     * @param null $default
     * @return mixed
     */
    function get($key, $default = null);

    /**
     * Sets the metadata for the provided key
     *
     * @param string $key
     * @param mixed $value
     */
    function set($key, $value);
}