<?php

namespace Gnutix\Library\Model;

/**
 * Book's category
 */
class Category extends ArrayPopulatedEntity
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
