<?php

declare(strict_types=1);

namespace Gnutix\Kernel;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Webmozart\Assert\Assert;

abstract class Kernel implements HttpKernelInterface
{
    private ?string $projectDir = null;
    private string $environment;
    private bool $debug;
    private ContainerInterface $container;

    public function __construct(string $environment = 'prod', bool $debug = false)
    {
        $this->environment = $environment;
        $this->debug = $debug;

        $this->initializeContainer();
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    protected function getEnvironment(): string
    {
        return $this->environment;
    }

    protected function getDebug(): bool
    {
        return $this->debug;
    }

    protected function getApplicationRootDir(): string
    {
        if (null === $this->projectDir) {
            $r = new \ReflectionObject($this);
            /** @var string $dir */
            $dir = $r->getFileName();
            Assert::fileExists($dir);

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

    protected function getConfigDir(): string
    {
        return $this->getApplicationRootDir().'/config';
    }

    protected function getCacheDir(): string
    {
        return $this->getApplicationRootDir().'/var/cache/'.$this->getEnvironment();
    }

    protected function getPublicDir(): string
    {
        return $this->getApplicationRootDir().'/public';
    }

    protected function getCharset(): string
    {
        return 'utf-8';
    }

    protected function getConfigFilesExtension(): string
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

        $this->addCompilerPasses($container);

        // Load the application's services files (so that it can override the extensions ones)
        $this->loadConfigurationFile($configLoader, 'services', $configExtension, false);

        // Compile everything
        $container->compile();

        // Store the container
        $this->container = $container;
    }

    protected function addCompilerPasses(ContainerBuilder $container): void
    {
    }

    protected function loadConfigurationFile(
        LoaderInterface $loader,
        string $fileNamePrefix,
        string $fileNameExtension,
        bool $throwException = true
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
    protected function getExtensions(): array
    {
        return [];
    }
}
