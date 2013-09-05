<?php

namespace Gnutix\Library;

/**
 * Library Factory Interface
 */
interface LibraryFactoryInterface
{
    /**
     * @return \Gnutix\Library\LibraryInterface
     */
    public function getLibrary();
}
