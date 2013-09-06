<?php

namespace Gnutix\StarWarsLibrary\Model;

/**
 * Chronological Marker
 */
class ChronologicalMarker
{
    /** @var string */
    protected $date;

    /** @var \Gnutix\StarWarsLibrary\Model\Era */
    protected $era;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->date = $data['date'];
        $this->era = $data['era'];
    }

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
