<?php

namespace Noob\Core\Exception;

/**
 * NullArgumentException
 *
 * @package Noob\Core\Exception
 */
class NullArgumentException extends \InvalidArgumentException {
    /**
     * Constructor
     *
     * @param string $argumentName
     * @param string $message
     */
    public function __construct($argumentName, $message) {
        $this->message = $argumentName.' '.strtolower($message);
    }
} 