<?php

namespace Gnutix\Library\Model;

/**
 * Release
 */
class Release extends ArrayPopulatedEntity
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
}
