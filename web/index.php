<?php

use Gnutix\Application\Kernel;

// Load the PSR-0 autoloader
$root = __DIR__.'/..';
require_once $root.'/vendor/autoload.php';

$application = new Kernel();
$container = $application->createContainerBuilder($root, $root.'/config');

// Add the deprecated function to generate the array
$container->get('twig')->addFunction(
    new Twig_SimpleFunction('deprecated_display_books_from_xml', 'displayBooksFromXml')
);

// Render the template
echo $container->get('twig')->render(
    'index.html.twig',
    array(
        'library' => $container->get('gnutix_library.library_factory')->getLibrary(),
    )
);
