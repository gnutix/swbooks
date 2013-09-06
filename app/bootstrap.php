<?php

// Debug config
error_reporting(-1);
ini_set('display_errors', 1);

// Load the PSR-0 autoloader
require_once __DIR__.'/../vendor/autoload.php';

// Build and return the DIC
return require_once __DIR__.'/container.php';
