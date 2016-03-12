<?php

namespace Gnutix\Library\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * Extension
 */
class Extension implements ExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configProcessor = new Processor();
        $config = $configProcessor->processConfiguration(new Configuration(), $config);

        $container->setParameter('gnutix_library.source_file_path', $config['source_file_path']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * {@inheritDoc}
     */
    public function getNamespace()
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function getXsdValidationBasePath()
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return 'gnutix_library';
    }
}
