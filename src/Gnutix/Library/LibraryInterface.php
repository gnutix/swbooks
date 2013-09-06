<?php

namespace Gnutix\Library;

/**
 * Library Interface
 */
interface LibraryInterface
{
    /**
     * Returns the raw data used to generate the library (for example an XML object)
     *
     * @return mixed
     */
    public function getRawData();

    /**
     * Returns the books of the library
     *
     * @return \Gnutix\Library\Model\Book[]
     */
    public function getBooks();
}
