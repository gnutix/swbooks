<?php

namespace Gnutix\Library\Model;

/**
 * Book's author
 */
class Author extends ArrayPopulatedEntity
{
    /** @var string */
    protected $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
