<?php

namespace Gnutix\Library\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;

/**
 * Book's author
 */
class Author extends ArrayPopulatedObject
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
        /* Hack to create an ID when there's none. Useful when dumping a library.
        if (null === $this->id && !empty($this->name)) {
            return strtolower(str_replace(array(' ', '-', '.'), array('_', '_', ''), $this->name));
        }
        */

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
