<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

$container = new ContainerBuilder();
$container->setParameter('root_dir', __DIR__.'/..');

// Load the various libraries YAML configurations
$containerConfigurations = array(
    __DIR__.'/../src/Gnutix/Library/Resources' => array('services.yml'),
    __DIR__.'/config' => array('services.yml'),
);

foreach ($containerConfigurations as $path => $files) {
    $loader = new YamlFileLoader($container, new FileLocator($path));
    foreach ($files as $file) {
        $loader->load($file);
    }
}

return $container;
