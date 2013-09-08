<?php

use Symfony\Component\HttpFoundation\Request;
use Gnutix\Application\Kernel;

require_once __DIR__.'/../vendor/autoload.php';

$kernel = new Kernel('dev');
$response = $kernel->handle(Request::createFromGlobals());
$response->send();
