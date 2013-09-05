<?php

namespace Gnutix\Library;

/**
 * Library Interface
 */
interface LibraryInterface
{
    /**
     * @return \Gnutix\Library\Model\Book[]
     */
    public function getBooks();
}
