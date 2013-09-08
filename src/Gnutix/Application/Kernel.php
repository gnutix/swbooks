<?php

namespace Gnutix\Application;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Gnutix\Library\DependencyInjection\Extension as GnutixLibraryExtension;
use Gnutix\TwigBridge\DependencyInjection\Extension as GnutixTwigBridgeExtension;

/**
 * Kernel
 */
class Kernel implements HttpKernelInterface
{
    /** @var string */
    protected $environment;

    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    protected $container;

    /**
     * @param string $environment
     */
    public function __construct($environment = 'prod')
    {
        $this->environment = $environment;

        $this->initializeContainer();
    }

    /**
     * Get the root directory of the application
     *
     * @return string
     */
    public function getRootDir()
    {
        return __DIR__.'/../../..';
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Extension\ExtensionInterface[]
     */
    protected function getExtensions()
    {
        return array(
            new GnutixTwigBridgeExtension(),
            new GnutixLibraryExtension(),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        // Generate the web page
        return new Response(
            $this->container->get('twig')->render(
                'index.html.twig',
                array(
                    'library' => $this->container->get('gnutix_library.library_factory')->getLibrary(),
                )
            )
        );
    }

    /**
     * Create the container
     */
    protected function initializeContainer()
    {
        // Create the container builder
        $this->container = new ContainerBuilder();

        // Set the root of the application
        $this->container->setParameter('kernel.root_dir', $this->getRootDir());
        $this->container->setParameter('kernel.environment', $this->environment);

        // Register the extensions before loading the configuration
        foreach ($this->getExtensions() as $extension) {
            $this->container->registerExtension($extension);
        }

        // Load the configuration files
        $loader = new YamlFileLoader($this->container, new FileLocator($this->getRootDir().'/config'));

        // Load a service file for a specific environment
        try {
            $loader->load('config_'.$this->environment.'.yml');
        } catch (\InvalidArgumentException $e) {
            $loader->load('config.yml');
        }

        // Load the extensions configuration
        foreach ($this->getExtensions() as $extension) {
            $this->container->getExtension($extension->getAlias())
                ->load($this->container->getExtensionConfig($extension->getAlias()), $this->container);
        }

        // Execute the compiler passes
        $this->container->compile();
    }
}
