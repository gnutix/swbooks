<?php

namespace Gnutix\StarWarsLibrary\Tests\Unit\Twig\Extension;

use Gnutix\Kernel\Tests\Unit\PhpUnitSimpleTestCase;
use Gnutix\StarWarsLibrary\Twig\Extension\StarWarsExtension;

/**
 * Star Wars Twig Extension Tests
 */
final class StarWarsExtensionTest extends PhpUnitSimpleTestCase
{
    /**
     * Set up the instance
     */
    protected function setUp(): void
    {
        $this->instance = new StarWarsExtension();
    }

    public function getSimpleMethodsData()
    {
        $bby = '&nbsp;<abbr title="Before the Battle of Yavin IV">BBY</abbr>';
        $aby = '&nbsp;<abbr title="After the Battle of Yavin IV">ABY</abbr>';

        return [
            ['transformToStarWarsDate', '', ''],
            ['transformToStarWarsDate', '42', '42'.$aby],
            ['transformToStarWarsDate', '42.5', '42.5'.$aby],
            ['transformToStarWarsDate', '-1337', '1337'.$bby],
            ['transformToStarWarsDate', '-1337.25', '1337.25'.$bby],
            ['transformToStarWarsDate', '42 BBY', '42'.$bby],
            ['transformToStarWarsDate', '-1337 BBY', '1337'.$bby],
            ['transformToStarWarsDate', '1337 BBY - 42 ABY', '1337'.$bby.' - 42'.$aby],
        ];
    }
}
