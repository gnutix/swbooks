<?php

namespace Gnutix\Library\Model;

use Gnutix\Library\LibraryInterface;

/**
 * Library manager
 */
class Library implements LibraryInterface
{
    /** @var \Gnutix\Library\Model\Book[] */
    protected $books;

    /** @var \Gnutix\Library\Model\Category[] */
    protected $categories;

    /** @var \Gnutix\Library\Model\Editor[] */
    protected $editors;

    /**
     * @param array $books
     * @param array $categories
     * @param array $editors
     */
    public function __construct(array $books, array $categories = array(), array $editors = array())
    {
        $this->books = $books;
        $this->categories = $categories;
        $this->editors = $editors;
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
