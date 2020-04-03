<?php

declare(strict_types=1);

namespace Gnutix\Library;

use Gnutix\Library\Model\Book;
use Gnutix\Library\Model\Category;
use Gnutix\Library\Model\Editor;

interface LibraryInterface
{
    /**
     * @return Book[]
     */
    public function getBooks(): array;

    /**
     * @return Editor[]
     */
    public function getEditors(): array;

    /**
     * @return Category[]
     */
    public function getCategories(): array;
}
