<?php

namespace Gnutix\Library\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;

/**
 * Books Owner
 */
final class Owner extends ArrayPopulatedObject
{
    /** @var string */
    protected $id;

    /** @var int */
    protected $nbCopies;

    /** @var int */
    protected $nbReadings;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getNbCopies()
    {
        return $this->nbCopies;
    }

    /**
     * @return int
     */
    public function getNbReadings()
    {
        return $this->nbReadings;
    }
}
