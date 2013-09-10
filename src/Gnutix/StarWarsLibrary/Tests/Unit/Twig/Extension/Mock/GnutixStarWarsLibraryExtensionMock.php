<?php

namespace Gnutix\StarWarsLibrary\Tests\Unit\Twig\Extension\Mock;

use Gnutix\StarWarsLibrary\Twig\Extension\GnutixStarWarsLibraryExtension;

/**
 * Gnutix Library Twig Extension Mock
 */
class GnutixStarWarsLibraryExtensionMock extends GnutixStarWarsLibraryExtension
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
