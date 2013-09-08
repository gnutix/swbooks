<?php

use Symfony\Component\HttpFoundation\Request;

$rootDir = __DIR__.'/..';
require_once $rootDir.'/vendor/autoload.php';

$kernel = new AppKernel($rootDir);
$response = $kernel->handle(Request::createFromGlobals());
$response->send();
