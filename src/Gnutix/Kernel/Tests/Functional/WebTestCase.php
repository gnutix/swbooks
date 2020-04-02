<?php

namespace Gnutix\Kernel\Tests\Functional;

use Application\AppKernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\HttpKernelBrowser;

/**
 * Web Test Case
 */
abstract class WebTestCase extends TestCase
{
    /** @var \Application\AppKernel */
    protected $kernel;

    /** @var \Symfony\Component\HttpKernel\HttpKernelBrowser */
    protected $httpKernelBrowser;

    /**
     * Create the application kernel for the functional tests
     */
    protected function setUp(): void
    {
        $this->kernel = new AppKernel('test', true);
        $this->httpKernelBrowser = new HttpKernelBrowser($this->kernel);
    }
}
