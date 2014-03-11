<?php

namespace Noob\Inject\Binding\Lifetime;

class TransientStrategy extends LifetimeStrategy {
    function resolve() {
        return $this->provider->provide();
    }
}