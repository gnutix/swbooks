<?php

use Gnutix\Library\Loader\XmlFileLoader;
use Gnutix\StarWarsLibrary\LibraryFactory\XmlLibraryFactory;

// Bootstrap the application
require_once __DIR__.'/../app/bootstrap.php';

// Build the library from the XML file
$xmlFileLoader = new XmlFileLoader(__DIR__.'/../data/books.xml');
$libraryFactory = new XmlLibraryFactory($xmlFileLoader);

// Render the template
echo $twig->render(
    'index.html.twig',
    array(
        'library' => $libraryFactory->getLibrary(),
    )
);
