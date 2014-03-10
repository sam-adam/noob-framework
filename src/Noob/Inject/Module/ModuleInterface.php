<?php

namespace Noob\Inject\Module;

use Noob\Inject\ContainerInterface;

/**
 * ModuleInterface
 * @package Noob\Inject\Module
 */
interface ModuleInterface {
    /**
     * Get the module's name
     *
     * @return string
     */
    function getName();

    /**
     * Called when the module is loaded into the container
     *
     * @param ContainerInterface $container
     */
    function onLoad(ContainerInterface $container);

    /**
     * Called when the module is unloaded from the container
     *
     * @param ContainerInterface $container
     */
    function onUnload(ContainerInterface $container);
}