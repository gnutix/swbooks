<?php

namespace Gnutix\Library;

/**
 * Library Factory Interface
 */
interface LibraryFactoryInterface
{
    /**
     * @return \Gnutix\Library\LibraryInterface
     *
     * @return string
     */
    public function getLibrary();

    /**
     * @return \Gnutix\Library\LibraryDumperInterface
     */
    public function getLibraryDumper();
}
