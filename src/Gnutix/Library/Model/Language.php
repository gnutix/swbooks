<?php

namespace Gnutix\Library\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;

/**
 * Book's language
 */
class Language extends ArrayPopulatedObject
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $name;

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
}
