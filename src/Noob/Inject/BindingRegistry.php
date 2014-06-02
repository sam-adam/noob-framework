<?php

namespace Noob\Inject;

use Noob\Inject\Bindings\TypeBinding;
use Noob\Inject\Types\Type;

class BindingRegistry {
    private $bindings = [];

    public function addBinding(TypeBinding $binding) {
        $this->bindings[] = $binding;
    }

    /**
     * @param Type $type
     * @return TypeBinding
     */
    public function getBindingForType(Type $type) {
        $bindings = array_filter($this->bindings, function(TypeBinding $binding) use($type) {
            return $binding->getService()->getName() === $type->getName();
        });

        return (count($bindings) > 0)
            ? $bindings[0]
            : null;
    }
}