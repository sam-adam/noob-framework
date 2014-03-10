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
} 