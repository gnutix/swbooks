<?php

use Application\AppKernel;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../vendor/autoload.php';

$kernel = new AppKernel('dev', true);
$response = $kernel->handle(Request::createFromGlobals());
$response->send();
