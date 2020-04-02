<?php

namespace Application\Tests\Functional;

use Gnutix\Kernel\Tests\Functional\WebTestCase;

/**
 * Books List Tests
 *
 * @group functional
 */
final class BooksListTest extends WebTestCase
{
    /** @var \Symfony\Component\DomCrawler\Crawler */
    protected $crawler;

    /**
     * Set up the test client
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->crawler = $this->httpKernelBrowser->request('GET', '/');
    }

    /**
     * Test the page status code
     */
    public function testPageStatusCode(): void
    {
        $this->assertSame(200, $this->httpKernelBrowser->getResponse()->getStatusCode());
    }

    /**
     * Tests the page title and description
     */
    public function testPageHeader(): void
    {
        $this->assertStringContainsString('Star Wars', $this->crawler->filter('h1')->text());
        $this->assertStringContainsString('list of Star Wars books', $this->crawler->filter('p')->text());
    }

    /**
     * Tests the editors list
     */
    public function testEditorsList(): void
    {
        $this->assertCount(3, $this->crawler->filter('#collapseEditors tbody tr'));

        $this->assertSame(
            'EN',
            $this->crawler->filter('#collapseEditors tbody tr')->first()->filter('img')->attr('alt')
        );
        $this->assertStringContainsString(
            'English Publisher',
            $this->crawler->filter('#collapseEditors tbody tr')->first()->filter('td')->last()->text()
        );

        $this->assertSame(
            'FR',
            $this->crawler->filter('#collapseEditors tbody tr')->eq(1)->filter('img')->attr('alt')
        );
        $this->assertStringContainsString(
            'French Publisher',
            $this->crawler->filter('#collapseEditors tbody tr')->eq(1)->filter('td')->last()->text()
        );
    }

    /**
     * Tests the categories list
     */
    public function testCategoriesList(): void
    {
        $this->assertCount(2, $this->crawler->filter('#collapseTypes li'));
        $this->assertStringContainsString('Novel', $this->crawler->filter('#collapseTypes li')->first()->text());
    }

    /**
     * Tests the eras list
     */
    public function testErasList(): void
    {
        $this->assertCount(3, $this->crawler->filter('#collapseEras ul li'));
        $this->assertStringContainsString(
            'Old Republic',
            $this->crawler->filter('#collapseEras a[href="#oldRepublic"]')->text()
        );
    }

    /**
     * Tests the book table
     *
     * @todo Complete this test...
     */
    public function testBooksTable(): void
    {
        $this->assertCount(1, $this->crawler->filter('table[data-books-list]'));
        $this->assertGreaterThan(1, $this->crawler->filter('table[data-books-list] thead')->count());
        $this->assertGreaterThan(1, $this->crawler->filter('table[data-books-list] tbody')->count());
        $this->assertGreaterThan(1, $this->crawler->filter('table[data-books-list] tbody td')->count());
    }
}