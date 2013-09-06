<?php

namespace Gnutix\StarWarsLibrary\Model;

use Gnutix\Library\Model\Book as BaseBook;

/**
 * Book
 */
class Book extends BaseBook
{
    /** @var \Gnutix\StarWarsLibrary\Model\ChronologicalMarker */
    protected $chronologicalMarker;

    /**
     * @return \Gnutix\StarWarsLibrary\Model\ChronologicalMarker
     */
    public function getChronologicalMarker()
    {
        return $this->chronologicalMarker;
    }
}
