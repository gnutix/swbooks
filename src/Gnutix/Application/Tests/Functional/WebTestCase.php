<?php

namespace Gnutix\Application\Tests\Functional;

use Symfony\Component\HttpKernel\Client;

/**
 * Web Test Case
 */
class WebTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var \Symfony\Component\HttpKernel\KernelInterface */
    protected $kernel;

    /** @var \Symfony\Component\HttpKernel\Client */
    protected $client;

    /**
     * Create the application kernel for the functional tests
     */
    public function __construct()
    {
        $this->kernel = new \AppKernel('test');
        $this->client = new Client($this->kernel);
    }
}
