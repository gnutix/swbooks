<?php

namespace Gnutix\TwigBridge\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;

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

        $container->register('twig.loader', 'Twig_Loader_Filesystem')
            ->addArgument($config['templates_paths']);

        $container->register('twig.environment', 'Twig_Environment')
            ->addArgument(new Reference('twig.loader'))
            ->addArgument($config['options']);

        $container->setAlias('twig', 'twig.environment');
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
        return 'gnutix_twig';
    }
}