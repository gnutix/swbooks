<?php

namespace Gnutix\StarWarsLibrary\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;

/**
 * Chronological Marker
 */
class ChronologicalMarker extends ArrayPopulatedObject
{
    /** @var string */
    protected $date;

    /** @var \Gnutix\StarWarsLibrary\Model\Era */
    protected $era;

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return \Gnutix\StarWarsLibrary\Model\Era
     */
    public function getEra()
    {
        return $this->era;
    }
}
