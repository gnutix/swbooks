<?php

namespace Gnutix\Application\Tests\Functional;

use Symfony\Component\HttpKernel\Client;

/**
 * Web Test Case
 */
class WebTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var \Symfony\Component\HttpKernel\Client */
    protected $client;

    /** @var \Symfony\Component\DomCrawler\Crawler */
    protected $crawler;

    /**
     * Set up the test client
     */
    public function setUp()
    {
        $this->client = new Client(new \AppKernel('test'));
        $this->crawler = $this->client->request('GET', '/');
    }
}
