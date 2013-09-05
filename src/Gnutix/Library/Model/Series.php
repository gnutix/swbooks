<?php

namespace Gnutix\Library\Model;

/**
 * Book Series
 */
class Series
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
