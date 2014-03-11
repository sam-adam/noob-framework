<?php

namespace Noob\Inject\Provider;

use Noob\Inject\Binding\Lifetime\LifetimeStrategyInterface;

abstract class Provider implements ProviderInterface {
    protected $lifetime;

    public function __construct(LifetimeStrategyInterface $lifetime) {
        $this->lifetime = $lifetime;
    }

    public function provide() {
        if(!$this->isInstantiated()) {
            $this->create();
        }
    }

    private function create() {

    }

    public function isInstantiated() {

    }
}