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

    /** @var int */
    protected $swuBookId;

    /**
     * @return \Gnutix\StarWarsLibrary\Model\ChronologicalMarker
     */
    public function getChronologicalMarker()
    {
        return $this->chronologicalMarker;
    }

    /**
     * @return int
     */
    public function getSwuBookId()
    {
        return $this->swuBookId;
    }
}
