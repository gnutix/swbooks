<?php

namespace Gnutix\Library\Model;

/**
 * Release
 */
class Release
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
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->title = $data['title'];
        $this->editor = $data['editor'];
        $this->date = (null !== $data['date']) ? new \DateTime($data['date']) : null;
        $this->language = $data['language'];
        $this->nbCopiesOwned = $data['nbCopiesOwned'];
        $this->nbReadings = $data['nbReadings'];
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
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
