<?php

namespace Gnutix\Library\Model;

/**
 * Book's author
 */
class Author
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
