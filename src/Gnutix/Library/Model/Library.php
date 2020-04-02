<?php

namespace Gnutix\Library\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;
use Gnutix\Library\LibraryInterface;

/**
 * Library manager
 */
class Library extends ArrayPopulatedObject implements LibraryInterface
{
    /** @var \Gnutix\Library\Model\Book[] */
    protected $books;

    /** @var \Gnutix\Library\Model\Category[] */
    protected $categories;

    /** @var \Gnutix\Library\Model\Editor[] */
    protected $editors;

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
