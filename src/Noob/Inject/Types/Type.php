<?php

namespace Noob\Inject\Types;

class Type extends \ReflectionClass {
    private $dependencies = [];

    public function __construct($name) {
        parent::__construct($name);

        if($this->getConstructor()) {
            array_walk($this->getConstructor()->getParameters(), function(\ReflectionParameter $param) {
                $this->dependencies[] = new Parameter($param);
            });
        }
    }

    public function getDependencies() {
        return $this->dependencies;
    }
}