<?php

namespace Gnutix\Library\Model;

use Gnutix\Library\Model;

/**
 * Book
 */
class Book
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
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->category = $data['category'];
        $this->series = $data['series'];
        $this->seriesBookId = $data['seriesBookId'];
        $this->author = $data['author'];
        $this->releases = $data['releases'];
    }

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
     * @return int
     */
    public function getNbCopiesOwned()
    {
        return $this->nbCopiesOwned;
    }

    /**
     * @return mixed
     */
    public function getNbReadings()
    {
        return $this->nbReadings;
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

    /**
     * @return int
     */
    public function getSeriesBookId()
    {
        return $this->seriesBookId;
    }
}
