<?php

namespace Gnutix\StarWarsLibrary\DependencyInjection;

use Gnutix\Library\DependencyInjection\Extension as LibraryExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Extension
 */
final class Extension extends LibraryExtension
{
    public function load(array $config, ContainerBuilder $container): void
    {
        parent::load($config, $container);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
