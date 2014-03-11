<?php

namespace Noob\Inject\Binding\Lifetime;

use Noob\Inject\Provider\ProviderInterface;

abstract class LifetimeStrategy implements LifetimeStrategyInterface {
    protected $provider;

    public function __construct(ProviderInterface $provider) {
        $this->provider = $provider;
    }

    abstract function resolve();
} 