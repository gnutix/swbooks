<?php

namespace Gnutix\StarWarsLibrary\Model;

use Gnutix\StarWarsLibrary\StarWarsLibraryInterface;
use Gnutix\Library\Model\Library as BaseLibrary;

/**
 * Star Wars Library
 */
class Library extends BaseLibrary implements StarWarsLibraryInterface
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

    public function getBooksByEra(string $eraId)
    {
        return array_filter($this->getBooks(), static function (Book $book) use ($eraId): bool {
            return $book->getChronologicalMarker()->getEra()->getId() === $eraId;
        });
    }
}
