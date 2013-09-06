<?php

namespace Gnutix\Library\Model;

/**
 * Book Series
 */
class Series
{
    /** @var string */
    protected $name;

    /** @var int */
    protected $bookId;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->bookId = $data['bookId'];
    }

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
