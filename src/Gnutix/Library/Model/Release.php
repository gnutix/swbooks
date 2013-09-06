<?php

namespace Gnutix\Library\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;

/**
 * Release
 */
class Release extends ArrayPopulatedObject
{
    /** @var string */
    protected $title;

    /** @var Editor */
    protected $editor;

    /** @var \DateTime */
    protected $date;

    /** @var string */
    protected $language;

    /** @var int */
    protected $nbCopiesOwned;

    /** @var int */
    protected $nbReadings;

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return null !== $this->date ? new \DateTime($this->date) : null;
    }

    /**
     * @return \Gnutix\Library\Model\Editor
     */
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
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
}
