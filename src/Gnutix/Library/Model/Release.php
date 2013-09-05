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
