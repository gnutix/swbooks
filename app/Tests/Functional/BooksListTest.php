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
        $this->assertCount(3, $this->crawler->filter('#collapseEditors tbody tr'));

        $this->assertEquals(
            'PEN',
            $this->crawler->filter('#collapseEditors tbody tr')->first()->filter('td')->first()->text()
        );
        $this->assertContains(
            'English Publisher',
            $this->crawler->filter('#collapseEditors tbody tr')->first()->filter('td')->last()->text()
        );

        $this->assertEquals(
            'PFR',
            $this->crawler->filter('#collapseEditors tbody tr')->eq(1)->filter('td')->first()->text()
        );
        $this->assertContains(
            'French Publisher',
            $this->crawler->filter('#collapseEditors tbody tr')->eq(1)->filter('td')->last()->text()
        );
    }

    /**
     * Tests the categories list
     */
    public function testCategoriesList()
    {
        $this->assertCount(2, $this->crawler->filter('#collapseTypes li'));
        $this->assertContains('Novel', $this->crawler->filter('#collapseTypes li')->first()->text());
    }

    /**
     * Tests the eras list
     */
    public function testErasList()
    {
        $this->assertCount(3, $this->crawler->filter('#collapseEras ul li'));
        $this->assertContains('Old Republic', $this->crawler->filter('#collapseEras a[href="#oldRepublic"]')->text());
    }

    /**
     * Tests the book table
     *
     * @todo Complete this test...
     */
    public function testBooksTable()
    {
        $this->assertCount(1, $this->crawler->filter('table[data-books-list]'));
        $this->assertGreaterThan(1, $this->crawler->filter('table[data-books-list] thead')->count());
        $this->assertGreaterThan(1, $this->crawler->filter('table[data-books-list] tbody')->count());
        $this->assertGreaterThan(1, $this->crawler->filter('table[data-books-list] tbody td')->count());
    }
}
