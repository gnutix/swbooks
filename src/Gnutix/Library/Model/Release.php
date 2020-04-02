<?php

namespace Gnutix\Library\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;

/**
 * Release
 */
final class Release extends ArrayPopulatedObject
{
    /** @var string */
    protected $title;

    /** @var \Gnutix\Library\Model\Language */
    protected $language;

    /** @var \Gnutix\Library\Model\Editor */
    protected $editor;

    /** @var \Gnutix\Library\Model\Format */
    protected $format;

    /** @var \DateTime */
    protected $publicationDate;

    /** @var \Gnutix\Library\Model\Series */
    protected $series;

    /** @var \Gnutix\Library\Model\Series */
    protected $owner;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return \Gnutix\Library\Model\Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return \Gnutix\Library\Model\Editor
     */
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * @return \Gnutix\Library\Model\Format
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @return \DateTime
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->publicationDate < new \DateTime();
    }

    /**
     * @return \Gnutix\Library\Model\Series
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * @return \Gnutix\Library\Model\Owner
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
