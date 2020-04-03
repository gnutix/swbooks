<?php

declare(strict_types=1);

namespace Gnutix\StarWarsLibrary\Model;

use Gnutix\Library\Model\Book as BaseBook;

final class Book extends BaseBook
{
    protected ChronologicalMarker $chronologicalMarker;
    protected ?int $swuBookId;

    public function getChronologicalMarker(): ChronologicalMarker
    {
        return $this->chronologicalMarker;
    }

    public function getSwuBookId(): ?int
    {
        return $this->swuBookId;
    }
}
