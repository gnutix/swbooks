<?php

namespace Gnutix\StarWarsLibrary\Tests\Unit\Twig\Extension;

use Gnutix\Kernel\Tests\Unit\PhpUnitSimpleTestCase;

use Gnutix\StarWarsLibrary\Tests\Unit\Twig\Extension\Mock\StarWarsExtensionMock;

/**
 * Star Wars Twig Extension Tests
 */
class GnutixStarWarsLibraryExtensionTest extends PhpUnitSimpleTestCase
{
    /**
     * Set up the instance
     */
    public function setUp()
    {
        $this->instance = new StarWarsExtensionMock;
    }

    /**
     * {@inheritDoc}
     */
    public function getSimpleMethodsData()
    {
        return array(
            array('transformToStarWarsDate', '', ''),
            array('transformToStarWarsDate', '42', '42 <ABY>'),
            array('transformToStarWarsDate', '-1337', '1337 <BBY>'),
            array('transformToStarWarsDate', '42 BBY', '42 <BBY>'),
            array('transformToStarWarsDate', '-1337 BBY', '1337 <BBY>'),
            array('transformToStarWarsDate', '1337 BBY - 42 ABY', '1337 <BBY> - 42 <ABY>'),
        );
    }
}
