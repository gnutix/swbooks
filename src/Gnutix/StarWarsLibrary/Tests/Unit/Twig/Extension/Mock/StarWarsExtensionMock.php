<?php

namespace Gnutix\StarWarsLibrary\Tests\Unit\Twig\Extension\Mock;

use Gnutix\StarWarsLibrary\Twig\Extension\StarWarsExtension;

/**
 * Star Wars Twig Extension Mock
 */
class StarWarsExtensionMock extends StarWarsExtension
{
    /**
     * {@inheritDoc}
     */
    protected function getStarWarsDateSuffixes()
    {
        return array(
            'BBY' => ' <BBY>',
            'ABY' => ' <ABY>',
        );
    }
}
