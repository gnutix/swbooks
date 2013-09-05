<?php

use Gnutix\Library\Loader\XmlFileLoader;
use Gnutix\StarWarsLibrary\LibraryFactory\XmlLibraryFactory;

require_once __DIR__.'/app/bootstrap.php';

// Build the library from the XML file
$xmlFileLoader = new XmlFileLoader(__DIR__.'/data/books.xml');
$libraryFactory = new XmlLibraryFactory($xmlFileLoader);
$library = $libraryFactory->getLibrary();

require_once __DIR__.'/template/index.php';
