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

    /**
     * Returns the books editors of the library
     *
     * @return \Gnutix\Library\Model\Editor[]
     */
    public function getEditors();

    /**
     * Returns the books categories of the library
     *
     * @return \Gnutix\Library\Model\Category[]
     */
    public function getCategories();
}
