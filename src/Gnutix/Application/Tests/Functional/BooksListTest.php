<?php

namespace Gnutix\Application\Tests\Functional;

/**
 * Books List Tests
 *
 * @group functional
 */
class BooksListTest extends WebTestCase
{
    /**
     * Test the page status code
     */
    public function testPageStatusCode()
    {
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Tests the page title and description
     */
    public function testPageHeader()
    {
        $this->assertContains('Star Wars', $this->crawler->filter('h1')->text());
        $this->assertContains('list of Star Wars books', $this->crawler->filter('p')->text());
    }
}
