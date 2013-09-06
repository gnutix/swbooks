<?php

namespace Gnutix\StarWarsLibrary\Model;

use Gnutix\Library\Model\ArrayPopulatedEntity;

/**
 * Chronological Era
 */
class Era extends ArrayPopulatedEntity
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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}
