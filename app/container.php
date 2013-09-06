<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

use Gnutix\Library\DependencyInjection\Extension as GnutixLibraryExtension;

/**
 * Prepare the extensions to load
 *
 * @var \Symfony\Component\DependencyInjection\Extension\ExtensionInterface[] $extensions
 */
$extensions = array(
    new GnutixLibraryExtension(),
);

// Create the container builder
$container = new ContainerBuilder();

// Set the root of the application
$container->setParameter('root_dir', __DIR__.'/..');

// Register the extensions before loading the configuration
foreach ($extensions as $extension) {
    $container->registerExtension($extension);
}

// Load the configuration files
$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/config'));
$loader->load('config.yml');

// Load the extensions configuration
foreach ($extensions as $extension) {
    $container->getExtension($extension->getAlias())
        ->load($container->getExtensionConfig($extension->getAlias()), $container);
}

return $container;
