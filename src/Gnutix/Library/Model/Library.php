<?php

namespace Gnutix\Library\Model;

use Gnutix\Library\LibraryInterface;
use Gnutix\Library\Helper\ArrayPopulatedObject;

/**
 * Library manager
 */
class Library extends ArrayPopulatedObject implements LibraryInterface
{
    /** @var mixed */
    protected $rawData;

    /** @var \Gnutix\Library\Model\Book[] */
    protected $books;

    /** @var \Gnutix\Library\Model\Category[] */
    protected $categories;

    /** @var \Gnutix\Library\Model\Editor[] */
    protected $editors;

    /**
     * @return mixed
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * {@inheritDoc}
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * @return \Gnutix\Library\Model\Editor[]
     */
    public function getEditors()
    {
        return $this->editors;
    }

    /**
     * @return \Gnutix\Library\Model\Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
