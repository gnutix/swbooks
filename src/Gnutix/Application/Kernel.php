<?php

namespace Gnutix\Application;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Kernel
 */
abstract class Kernel implements HttpKernelInterface
{
    /** @var string */
    protected $rootDir;

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
     * @return string
     */
    protected function getApplicationRootDir()
    {
        return realpath($this->getRootDir().'/..');
    }

    /**
     * @return string
     */
    protected function getConfigDir()
    {
        return $this->getRootDir().'/config';
    }

    /**
     * @return string
     */
    protected function getCacheDir()
    {
        return $this->getRootDir().'/cache/'.$this->environment;
    }

    /**
     * @return string
     */
    protected function getWebDir()
    {
        return $this->getApplicationRootDir().'/web';
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
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected function setKernelParameters(ContainerBuilder $container)
    {
        $container->setParameter('kernel.app_root_dir', $this->getApplicationRootDir());
        $container->setParameter('kernel.web_dir', $this->getWebDir());
        $container->setParameter('kernel.cache_dir', $this->getCacheDir());
        $container->setParameter('kernel.environment', $this->environment);

        return $container;
    }

    /**
     * Create the container
     */
    protected function initializeContainer()
    {
        // Create the container builder
        $container = $this->setKernelParameters(new ContainerBuilder());

        // Create a loader for the configuration files
        $configLoader = $this->getConfigFilesLoader($container);
        $configExtension = $this->getConfigFilesExtension();

        // Register the extensions before loading the configuration
        foreach ($this->getExtensions() as $extension) {
            $container->registerExtension($extension);
        }
        
        // Load the application's configuration files (so that it can configure the extensions)
        $this->loadConfigurationFile($configLoader, 'config', $configExtension);

        // Load the extensions configuration and services files
        foreach ($container->getExtensions() as $extension) {
            $extension->load($container->getExtensionConfig($extension->getAlias()), $container);
        }

        // Load the application's services files (so that it can override the extensions ones)
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
    protected function getExtensions()
    {
        return array();
    }

    /**
     * @return string
     *
     * @see \Symfony\Component\HttpKernel\Kernel::getRootDir()
     * @author Fabien Potencier <fabien@symfony.com>
     */
    protected function getRootDir()
    {
        if (null === $this->rootDir) {
            $r = new \ReflectionObject($this);
            $this->rootDir = str_replace('\\', '/', dirname($r->getFileName()));
        }

        return $this->rootDir;
    }
}
