<?php

namespace Tests\Functional;

use Gnutix\Kernel\Tests\Functional\WebTestCase;

/**
 * Books List Tests
 *
 * @group functional
 */
class BooksListTest extends WebTestCase
{
    /** @var \Symfony\Component\DomCrawler\Crawler */
    protected $crawler;

    /**
     * Set up the test client
     */
    public function setUp()
    {
        $this->crawler = $this->client->request('GET', '/');
    }

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

    /**
     * Tests the editors list
     */
    public function testEditorsList()
    {
        $this->assertCount(3, $this->crawler->filter('dl.editors dd'));

        $this->assertEquals('en', $this->crawler->filter('dl.editors dt abbr')->first()->attr('lang'));
        $this->assertEquals('English Publisher', $this->crawler->filter('dl.editors dd')->first()->text());

        $this->assertEquals('fr', $this->crawler->filter('dl.editors dt abbr')->eq(1)->attr('lang'));
        $this->assertContains('French Publisher', $this->crawler->filter('dl.editors dd')->eq(1)->text());
    }

    /**
     * Tests the categories list
     */
    public function testCategoriesList()
    {
        $this->assertCount(2, $this->crawler->filter('ul.legend li'));
        $this->assertEquals('Novel', $this->crawler->filter('ul.legend li.novel')->text());
    }

    /**
     * Tests the eras list
     */
    public function testErasList()
    {
        $this->assertCount(3, $this->crawler->filter('ul.shortcuts li'));
        $this->assertEquals('Old Republic', $this->crawler->filter('ul.shortcuts li.oldRepublic')->text());
    }

    /**
     * Tests the book table
     *
     * @todo Complete this test...
     */
    public function testBooksTable()
    {
        $this->assertCount(1, $this->crawler->filter('table.books-list'));
        $this->assertGreaterThan(1, $this->crawler->filter('table.books-list thead')->count());
        $this->assertGreaterThan(1, $this->crawler->filter('table.books-list tbody')->count());
        $this->assertGreaterThan(1, $this->crawler->filter('table.books-list tbody td')->count());
    }
}
