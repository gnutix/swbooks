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
     * @param array $books
     * @param array $categories
     * @param array $editors
     * @param array $eras
     */
    public function __construct(array $books, array $categories, array $editors, array $eras)
    {
        parent::__construct($books, $categories, $editors);

        $this->eras = $eras;
    }

    /**
     * @return \Gnutix\StarWarsLibrary\Model\Era[]
     */
    public function getEras()
    {
        return $this->eras;
    }
}
