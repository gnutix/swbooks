<?php

namespace Gnutix\StarWarsLibrary\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;

/**
 * Chronological Marker
 */
class ChronologicalMarker extends ArrayPopulatedObject
{
    /** @var int */
    protected $timeStart;

    /** @var int */
    protected $timeEnd;

    /** @var \Gnutix\StarWarsLibrary\Model\Era */
    protected $era;

    /**
     * @return int
     */
    public function getTimeStart()
    {
        return $this->timeStart;
    }

    /**
     * @return int
     */
    public function getTimeEnd()
    {
        return $this->timeEnd;
    }

    /**
     * @return \Gnutix\StarWarsLibrary\Model\Era
     */
    public function getEra()
    {
        return $this->era;
    }
}
