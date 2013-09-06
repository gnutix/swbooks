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

    /** @var \Gnutix\Library\Model\Series */
    protected $series;

    /** @var \Gnutix\Library\Model\Author */
    protected $author;

    /** @var \Gnutix\Library\Model\Release[] */
    protected $releases;

    /**
     * @return \Gnutix\Library\Model\Author
     */
    public function getAuthor()
    {
        return $this->author;
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

    /**
     * @return \Gnutix\Library\Model\Series
     */
    public function getSeries()
    {
        return $this->series;
    }
}
