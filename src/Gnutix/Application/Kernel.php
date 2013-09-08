<?php

namespace Gnutix\Application;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Gnutix\Application\DependencyInjection\Extension as GnutixApplicationExtension;

/**
 * Kernel
 */
abstract class Kernel implements HttpKernelInterface
{
    /** @var string */
    protected $applicationRootDir;

    /** @var string */
    protected $environment;

    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    protected $container;

    /**
     * @param string $applicationRootDir
     * @param string $environment
     */
    public function __construct($applicationRootDir, $environment = 'prod')
    {
        $this->applicationRootDir = $applicationRootDir;
        $this->environment = $environment;

        $this->initializeContainer();
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Extension\ExtensionInterface[]
     */
    abstract protected function getThirdPartyExtensions();

    /**
     * @return string
     */
    protected function getRootDir()
    {
        return realpath($this->applicationRootDir);
    }

    /**
     * @return string
     */
    protected function getKernelDir()
    {
        return $this->getRootDir().'/app';
    }

    /**
     * @return string
     */
    protected function getConfigDir()
    {
        return $this->getKernelDir().'/config';
    }

    /**
     * @return string
     */
    protected function getWebDir()
    {
        return $this->getRootDir().'/web';
    }

    /**
     * @return string
     */
    protected function getConfigFilesExtension()
    {
        return 'yml';
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return \Symfony\Component\Config\Loader\LoaderInterface
     */
    protected function getConfigFilesLoader(ContainerBuilder $container)
    {
        return new YamlFileLoader($container, new FileLocator($this->getConfigDir()));
    }

    /**
     * Create the container
     */
    protected function initializeContainer()
    {
        // Create the container builder
        $container = new ContainerBuilder();

        // Create a loader for the configuration files
        $configLoader = $this->getConfigFilesLoader($container);
        $configExtension = $this->getConfigFilesExtension();

        // Create useful parameters
        $container->setParameter('app.root_dir', $this->getRootDir());
        $container->setParameter('app.web_dir', $this->getWebDir());
        $container->setParameter('kernel.root_dir', $this->getKernelDir());
        $container->setParameter('kernel.environment', $this->environment);

        // Register the extensions before loading the configuration
        foreach ($this->getExtensions() as $extension) {
            $container->registerExtension($extension);
        }
        
        // Load the application's configuration files
        $this->loadConfigurationFile($configLoader, 'config', $configExtension);

        // Load the extensions configuration
        foreach ($container->getExtensions() as $extension) {
            $extension->load($container->getExtensionConfig($extension->getAlias()), $container);
        }

        // Load the application's configuration files
        $this->loadConfigurationFile($configLoader, 'services', $configExtension, false);

        // Compile everything
        $container->compile();
        
        // Store the container
        $this->container = $container;
    }

    /**
     * @param \Symfony\Component\Config\Loader\LoaderInterface $loader
     * @param string                                           $fileNamePrefix
     * @param string                                           $fileNameExtension
     * @param bool                                             $throwException
     *
     * @throws \InvalidArgumentException
     */
    protected function loadConfigurationFile(
        LoaderInterface $loader,
        $fileNamePrefix,
        $fileNameExtension,
        $throwException = true
    ) {
        // Try to load the environment specific configuration files
        try {
            $loader->load($fileNamePrefix.'_'.$this->environment.'.'.$fileNameExtension);
        } catch (\InvalidArgumentException $e) {

            // Try to load the environment agnostic configuration file
            try {
                $loader->load($fileNamePrefix.'.'.$fileNameExtension);
            } catch (\InvalidArgumentException $e) {

                // If the application can't work without this file, we throw the exception
                if ($throwException) {
                    throw $e;
                }
            }
        }
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Extension\ExtensionInterface[]
     */
    private function getExtensions()
    {
        return array_merge(array(new GnutixApplicationExtension()), $this->getThirdPartyExtensions());
    }
}
