<?php

namespace Noob\Inject\Bindings;

use Noob\Inject\Types\Type;

class TypeBinding {
    private $impl;
    private $service;

    public function __construct(Type $impl, Type $service) {
        $this->impl = $impl;
        $this->service = $service;

        if(!$this->validateBinding()) {
            throw new \Exception('Binding exception');
        }
    }

    public function getImplementer() {
        return $this->impl;
    }

    public function getService() {
        return $this->service;
    }

    private function validateBinding() {
        return (!$this->impl->isAbstract())
            && (!$this->impl->isInterface())
            && ($this->impl->isSubclassOf($this->service));
    }
}