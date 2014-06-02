<?php

require_once('vendor/autoload.php');

interface A {}

class AImplementer implements A {}

class B {}

$registry = new \Noob\Inject\BindingRegistry();
$registry->addBinding(new \Noob\Inject\Bindings\TypeBinding(new \Noob\Inject\Types\Type('AImplementer'), new \Noob\Inject\Types\Type('A')));

$resolver = new \Noob\Inject\Resolver($registry);
var_dump($resolver->resolve(new \Noob\Inject\Types\Type('B')));