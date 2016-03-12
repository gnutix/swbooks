<?php

namespace Gnutix\Library\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;

/**
 * Books Editor
 */
class Editor extends ArrayPopulatedObject
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $preferredLanguage;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPreferredLanguage()
    {
        return $this->preferredLanguage;
    }
}
