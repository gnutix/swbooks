<?php

namespace Gnutix\StarWarsLibrary\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;

/**
 * Chronological Era
 */
final class Era extends ArrayPopulatedObject
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

    public function getName()
    {
        return $this->name;
    }
}
