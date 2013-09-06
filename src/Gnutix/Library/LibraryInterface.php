<?php

namespace Gnutix\Library;

/**
 * Library Interface
 */
interface LibraryInterface
{
    /**
     * Returns the books of the library
     *
     * @return \Gnutix\Library\Model\Book[]
     */
    public function getBooks();
}
