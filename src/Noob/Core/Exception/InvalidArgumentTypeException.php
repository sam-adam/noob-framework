<?php

namespace Noob\Core\Exception;

/**
 * InvalidArgumentTypeException
 *
 * @package Noob\Core
 */
class InvalidArgumentTypeException extends \InvalidArgumentException {
    /**
     * Constructor
     *
     * @param string $required
     * @param mixed $given
     */
    public function __construct($required, $given) {
        $this->message = strtoupper($required).'required, '.gettype($given).' given.';
    }
} 