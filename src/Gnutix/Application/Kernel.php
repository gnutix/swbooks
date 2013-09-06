<?php

namespace Gnutix\Application;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

use Gnutix\Library\DependencyInjection\Extension as GnutixLibraryExtension;
use Gnutix\TwigBridge\DependencyInjection\Extension as GnutixTwigBridgeExtension;

/**
 * Kernel
 */
class Kernel
{
    /**
     * @return \Symfony\Component\DependencyInjection\Extension\ExtensionInterface[]
     */
    public function getExtensions()
    {
        return array(
            new GnutixTwigBridgeExtension(),
            new GnutixLibraryExtension(),
        );
    }

    /**
     * @param string $applicationRootPath The application root folder path
     * @param string $configurationPath   The configuration folder path
     *
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    public function createContainerBuilder($applicationRootPath, $configurationPath)
    {
        // Create the container builder
        $container = new ContainerBuilder();

        // Set the root of the application
        $container->setParameter('root_dir', $applicationRootPath);

        // Register the extensions before loading the configuration
        foreach ($this->getExtensions() as $extension) {
            $container->registerExtension($extension);
        }

        // Load the configuration files
        $loader = new YamlFileLoader($container, new FileLocator($configurationPath));
        $loader->load('config.yml');

        // Load the extensions configuration
        foreach ($this->getExtensions() as $extension) {
            $container->getExtension($extension->getAlias())
                ->load($container->getExtensionConfig($extension->getAlias()), $container);
        }

        return $container;
    }
}
