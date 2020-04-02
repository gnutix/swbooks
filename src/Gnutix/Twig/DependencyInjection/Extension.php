<?php

namespace Gnutix\Twig\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;

use Symfony\Bundle\TwigBundle\DependencyInjection\Configuration as SymfonyTwigConfiguration;
use Symfony\Bundle\TwigBundle\DependencyInjection\Compiler\TwigEnvironmentPass as SymfonyTwigEnvironmentPass;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Extension
 */
class Extension implements ExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configProcessor = new Processor();
        $config = $configProcessor->processConfiguration(new SymfonyTwigConfiguration(), $configs);

        // Create Twig's filesystem loader definition
        $twigLoaderDefinition = $container->register('twig.loader.filesystem', FilesystemLoader::class);

        // Add the paths from the configuration
        foreach ($config['paths'] as $path => $namespace) {
            $twigLoaderDefinition->addMethodCall('addPath', $namespace ? array($path, $namespace) : array($path));
        }

        // Create Twig's environment definition
        $twigDefinition = $container->register('twig', Environment::class)
            ->setPublic(true)
            ->addArgument(new Reference('twig.loader.filesystem'));

        // Add the global variables
        foreach ($config['globals'] as $key => $global) {

            // Allows to reference services
            if (isset($global['type']) && 'service' === $global['type']) {
                $global['value'] = new Reference($global['id']);
            }

            $twigDefinition->addMethodCall('addGlobal', array($key, $global['value']));
        }

        // Add the Twig's options argument
        $twigOptions = $config;
        foreach (array('form', 'globals', 'extensions') as $key) {
            unset($twigOptions[$key]);
        }
        $twigDefinition->addArgument($twigOptions);

        // Load the services
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // Add the TwigBundle's compiler passes, so that we can create extensions easily
        //$container->addCompilerPass(new SymfonyTwigEnvironmentPass());
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
        return 'twig';
    }
}
