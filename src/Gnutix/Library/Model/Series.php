<?php

namespace Gnutix\Library\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;

/**
 * Book Series
 */
class Series extends ArrayPopulatedObject
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
