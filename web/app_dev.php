<?php

use Symfony\Component\HttpFoundation\Request;
use Application\AppKernel;

require_once __DIR__.'/../vendor/autoload.php';

$kernel = new AppKernel('dev', true);
$response = $kernel->handle(Request::createFromGlobals());
$response->send();
