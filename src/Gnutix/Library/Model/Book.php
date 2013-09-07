<?php

namespace Gnutix\Library\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;

/**
 * Book
 */
class Book extends ArrayPopulatedObject
{
    /** @var \Gnutix\Library\Model\Category */
    protected $category;

    /** @var \Gnutix\Library\Model\Author[] */
    protected $authors;

    /** @var \Gnutix\Library\Model\Release[] */
    protected $releases;

    /**
     * @return \Gnutix\Library\Model\Author[]
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * @return \Gnutix\Library\Model\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return \Gnutix\Library\Model\Release[]
     */
    public function getReleases()
    {
        return $this->releases;
    }
}
