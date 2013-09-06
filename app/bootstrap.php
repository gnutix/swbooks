<?php

// Debug config
error_reporting(-1);
ini_set('display_errors', 1);

// Autoloader
require_once __DIR__.'/../vendor/autoload.php';

// Prepare Twig
$loader = new Twig_Loader_Filesystem(__DIR__.'/../web/views');
$twig = new Twig_Environment($loader, array('cache' => __DIR__.'/../cache/twig'));

// Add the deprecated functions to generate the array
require_once __DIR__.'/../src/functions.php';
$twig->addFunction(new Twig_SimpleFunction('deprecated_display_books_from_xml', 'displayBooksFromXml'));
