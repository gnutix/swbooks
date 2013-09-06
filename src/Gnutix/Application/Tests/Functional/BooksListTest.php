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

    /**
     * Tests the editors list
     */
    public function testEditorsList()
    {
        $this->assertCount(4, $this->crawler->filter('dl.editors dd'));

        $this->assertEquals('en', $this->crawler->filter('dl.editors dt abbr')->first()->attr('lang'));
        $this->assertEquals('Del Rey', $this->crawler->filter('dl.editors dd')->first()->text());

        $this->assertEquals('fr', $this->crawler->filter('dl.editors dt abbr')->last()->attr('lang'));
        $this->assertContains('Presses', $this->crawler->filter('dl.editors dd')->last()->text());
    }

    /**
     * Tests the categories list
     */
    public function testCategoriesList()
    {
        $this->assertCount(3, $this->crawler->filter('ul.legend li'));
        $this->assertEquals('Adult novel', $this->crawler->filter('ul.legend li.adult')->text());
    }

    /**
     * Tests the eras list
     */
    public function testErasList()
    {
        $this->assertCount(2, $this->crawler->filter('ul.shortcuts li'));
        $this->assertEquals('Old Republic era', $this->crawler->filter('ul.shortcuts li.oldRepublic')->text());
    }
}
