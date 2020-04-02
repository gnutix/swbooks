<?php

namespace Gnutix\Kernel;

use Symfony\Bundle\TwigBundle\DependencyInjection\Compiler\TwigEnvironmentPass as SymfonyTwigEnvironmentPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Kernel
 */
abstract class Kernel implements HttpKernelInterface
{
    /** @var string */
    protected $projectDir;

    /** @var string */
    protected $environment;

    /** @var bool */
    protected $debug;

    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    protected $container;

    /**
     * @param string $environment
     * @param bool   $debug
     */
    public function __construct($environment = 'prod', $debug = false)
    {
        $this->environment = $environment;
        $this->debug = $debug;

        $this->initializeContainer();
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return string
     */
    protected function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @return bool
     */
    protected function getDebug()
    {
        return $this->debug;
    }

    /**
     * @return string
     */
    protected function getApplicationRootDir()
    {
        if (null === $this->projectDir) {
            $r = new \ReflectionObject($this);

            if (!file_exists($dir = $r->getFileName())) {
                throw new \LogicException(sprintf(
                    'Cannot auto-detect project dir for kernel of class "%s".',
                    $r->name
                ));
            }

            $dir = $rootDir = \dirname($dir);
            while (!file_exists($dir.'/composer.json')) {
                if ($dir === \dirname($dir)) {
                    return $this->projectDir = $rootDir;
                }
                $dir = \dirname($dir);
            }
            $this->projectDir = $dir;
        }

        return $this->projectDir;
    }

    /**
     * @return string
     */
    protected function getConfigDir()
    {
        return $this->getApplicationRootDir().'/config';
    }

    /**
     * @return string
     */
    protected function getCacheDir()
    {
        return $this->getApplicationRootDir().'/var/cache/'.$this->getEnvironment();
    }

    /**
     * @return string
     */
    protected function getPublicDir()
    {
        return $this->getApplicationRootDir().'/public';
    }

    /**
     * @return string
     */
    protected function getCharset()
    {
        return 'utf-8';
    }

    /**
     * @return string
     */
    protected function getConfigFilesExtension()
    {
        return 'yml';
    }

    /**
     * @return \Symfony\Component\Config\Loader\LoaderInterface
     */
    protected function getConfigFilesLoader(ContainerBuilder $container)
    {
        return new YamlFileLoader($container, new FileLocator($this->getConfigDir()));
    }

    protected function addKernelParameters(ContainerBuilder $container): void
    {
        // Folder paths
        $container->setParameter('kernel.project_dir', $this->getApplicationRootDir());
        $container->setParameter('kernel.public_dir', $this->getPublicDir());
        $container->setParameter('kernel.cache_dir', $this->getCacheDir());

        // Configurations
        $container->setParameter('kernel.environment', $this->getEnvironment());
        $container->setParameter('kernel.debug', $this->getDebug());
        $container->setParameter('kernel.charset', $this->getCharset());
    }

    /**
     * Create the container
     */
    protected function initializeContainer(): void
    {
        // Create the container builder
        $container = new ContainerBuilder();
        $this->addKernelParameters($container);

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

        $container->addCompilerPass(new SymfonyTwigEnvironmentPass());

        // Load the application's services files (so that it can override the extensions ones)
        $this->loadConfigurationFile($configLoader, 'services', $configExtension, false);

        // Compile everything
        $container->compile();

        // Store the container
        $this->container = $container;
    }

    /**
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
    ): void {
        // Try to load the environment specific configuration files
        try {
            $loader->load($fileNamePrefix.'_'.$this->getEnvironment().'.'.$fileNameExtension);
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
        return [];
    }
}
