<?php

namespace Gnutix\TwigBridge\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;

use Symfony\Bundle\TwigBundle\DependencyInjection\Compiler\TwigEnvironmentPass;

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

        // Add the TwigBundle's compiler passes, so that we can create extensions easily
        $container->addCompilerPass(new TwigEnvironmentPass());
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
