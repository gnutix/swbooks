<?php

namespace Gnutix\Library\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;

/**
 * Book Series
 */
class Series extends ArrayPopulatedObject
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $title;

    /** @var int */
    protected $bookId;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getBookId()
    {
        return $this->bookId;
    }
}
