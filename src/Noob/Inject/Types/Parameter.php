<?php

namespace Noob\Inject\Types;

class Parameter extends \ReflectionParameter {
    private $type = null;

    public function __construct(\ReflectionParameter $parameter) {
        parent::__construct($parameter->getDeclaringFunction(), $parameter->getName());

        if($this->getClass()) {
            $this->type = new Type($this->getClass());
        }
    }

    public function isScalar() {
        return $this->type == null;
    }

    public function getType() {
        return $this->type ?: null;
    }
}