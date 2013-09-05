<?php

// Load the configuration for the script
require_once __DIR__.'/config.php';

// Load the old functions
require_once __DIR__.'/../src/functions.php';

// PSR-0 autoloader
require_once __DIR__.'/../src/SplClassLoader.php';

$classLoader = new SplClassLoader(null, __DIR__.'/../src');
$classLoader->register();
