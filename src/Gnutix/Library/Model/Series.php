<?php

namespace Gnutix\Library\Model;

/**
 * Book Series
 */
class Series extends ArrayPopulatedEntity
{
    /** @var string */
    protected $name;

    /** @var int */
    protected $bookId;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getBookId()
    {
        return $this->bookId;
    }
}
