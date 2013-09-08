<?php

use Symfony\Component\HttpFoundation\Request;
use Gnutix\Application\Kernel;

require_once __DIR__.'/../vendor/autoload.php';

$kernel = new Kernel('test');
$response = $kernel->handle(Request::createFromGlobals());
$response->send();
