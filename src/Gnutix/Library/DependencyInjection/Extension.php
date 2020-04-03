<?php

declare(strict_types=1);

namespace Gnutix\Library\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;

class Extension implements ExtensionInterface
{
    public function load(array $config, ContainerBuilder $container): void
    {
        $configProcessor = new Processor();
        $config = $configProcessor->processConfiguration(new Configuration(), $config);

        $container->setParameter('gnutix_library.source_file_path', $config['source_file_path']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setAliases($this->getLibraryAliases($config['source_file_path']));
    }

    public function getNamespace(): string
    {
        return '';
    }

    public function getXsdValidationBasePath(): string
    {
        return '';
    }

    public function getAlias(): string
    {
        return 'gnutix_library';
    }

    private function getLibraryAliases(string $sourceFilePath): array
    {
        $libraryType = strtolower(pathinfo($sourceFilePath, PATHINFO_EXTENSION));

        if ('yml' === $libraryType) {
            $libraryType = 'yaml';
        }

        return [
            'gnutix_library.loader' => 'gnutix_library.loader.'.$libraryType.'_file_loader',
            'gnutix_library.library_factory' => 'gnutix_library.library_factory.'.$libraryType.'_library_factory',
        ];
    }
}
