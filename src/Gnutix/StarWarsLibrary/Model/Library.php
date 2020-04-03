<?php

declare(strict_types=1);

namespace Gnutix\StarWarsLibrary\Model;

use Gnutix\Library\Model\Library as BaseLibrary;
use Gnutix\StarWarsLibrary\StarWarsLibraryInterface;

final class Library extends BaseLibrary implements StarWarsLibraryInterface
{
    /** @var Era[] */
    protected array $eras;

    /**
     * @return Era[]
     */
    public function getEras(): array
    {
        return $this->eras;
    }

    /**
     * @return Book[]
     */
    public function getBooksByEra(string $eraId): array
    {
        /** @var Book[] $books */
        $books = array_filter($this->getBooks(), static function (Book $book) use ($eraId): bool {
            return $book->getChronologicalMarker()->getEra()->getId() === $eraId;
        });

        return $books;
    }
}
