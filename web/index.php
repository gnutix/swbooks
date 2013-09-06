<?php

$container = require_once __DIR__.'/../app/bootstrap.php';

// Add the deprecated functions to generate the array
require_once __DIR__.'/../src/functions.php';
$container->get('gnutix_library.twig.environment')->addFunction(
    new Twig_SimpleFunction('deprecated_display_books_from_xml', 'displayBooksFromXml')
);

// Render the template
echo $container->get('gnutix_library.twig.environment')->render(
    'index.html.twig',
    array(
        'library' => $container->get('gnutix_library.library_factory')->getLibrary(),
    )
);
