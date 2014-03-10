<?php

namespace Noob\Inject\Module;

use Noob\Inject\ContainerInterface;

class BaseModule implements ModuleInterface {
    /**
     * Called when the module is loaded into the container
     *
     * @param ContainerInterface $container
     */
    function onLoad(ContainerInterface $container)
    {
        // TODO: Implement onLoad() method.
    }

    /**
     * Called when the module is unloaded from the container
     *
     * @param ContainerInterface $container
     */
    function onUnload(ContainerInterface $container)
    {
        // TODO: Implement onUnload() method.
    }

    /**
     * Get the module's name
     *
     * @return string
     */
    function getName()
    {
        // TODO: Implement getName() method.
    }
}