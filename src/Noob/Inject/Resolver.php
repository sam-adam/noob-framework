<?php

namespace Noob\Inject;

use Noob\Inject\Types\Parameter;
use Noob\Inject\Types\Type;

class Resolver {
    private $registry;

    public function __construct(BindingRegistry $registry) {
        $this->registry = $registry;
    }

    public function resolve(Type $type) {
        $binding = $this->registry->getBindingForType($type);

        if($binding) {
            return $this->resolve($binding->getImplementer());
        }

        $args = [];

        array_walk($type->getDependencies(), function(Parameter $param) use($args) {
            $args[] = $this->resolve($param->getType());
        });

        return $type->newInstanceArgs($args);
    }
}