<?php

namespace Noob\Core;

use Noob\Core\Exception\NullArgumentException;

/**
 * Enforce
 * @package Noob\Core
 */
class Enforce {
    /**
     * Enforce the given argument is not null
     *
     * @param mixed $argument
     * @param string $argumentName
     * @throws Exception\NullArgumentException
     */
    public static function ArgumentNotNull($argument, $argumentName) {
        if($argument === null) {
            throw new NullArgumentException($argumentName, 'Cannot be null');
        }
    }

    /**
     * Enforce the given string argument is not empty
     *
     * @param string $argument
     * @param string $argumentName
     * @throws \InvalidArgumentException
     */
    public static function ArgumentNotNullOrEmpty($argument, $argumentName) {
        if($argument === null || $argument === '') {
            throw new \InvalidArgumentException($argumentName.' cannot be null or empty');
        }
    }
} 