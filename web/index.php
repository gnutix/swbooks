<?php

$container = require_once __DIR__.'/../app/bootstrap.php';

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
