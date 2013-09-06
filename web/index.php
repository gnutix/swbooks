<?php

use Symfony\Component\HttpFoundation\Request;
use Gnutix\Application\Kernel;

// Load the PSR-0 autoloader
$root = __DIR__.'/..';
require_once $root.'/vendor/autoload.php';

$kernel = new Kernel();
$response = $kernel->handle(Request::createFromGlobals());
$response->send();
