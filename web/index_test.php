<?php

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../vendor/autoload.php';

$kernel = new AppKernel('test');
$response = $kernel->handle(Request::createFromGlobals());
$response->send();
