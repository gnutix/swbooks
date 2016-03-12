<?php

namespace Gnutix\StarWarsLibrary\Model;

use Gnutix\Library\Model\Library as BaseLibrary;

/**
 * Star Wars Library
 */
class Library extends BaseLibrary
{
    /** @var \Gnutix\StarWarsLibrary\Model\Era[] */
    protected $eras;

    /**
     * @return \Gnutix\StarWarsLibrary\Model\Era[]
     */
    public function getEras()
    {
        return $this->eras;
    }
}
