<?php

declare(strict_types=1);

namespace Gnutix\StarWarsLibrary;

use Gnutix\Library\LibraryInterface;

/**
 * Star Wars Library Interface
 */
interface StarWarsLibraryInterface extends LibraryInterface
{
    /**
     * @return \Gnutix\StarWarsLibrary\Model\Era[]
     */
    public function getEras();
}
