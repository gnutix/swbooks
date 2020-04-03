<?php

declare(strict_types=1);

namespace Gnutix\Library\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;
use Gnutix\Library\LibraryInterface;

class Library extends ArrayPopulatedObject implements LibraryInterface
{
    /** @var Book[] */
    protected array $books;

    /** @var Category[] */
    protected array $categories;

    /** @var Editor[] */
    protected array $editors;

    /**
     * @return Book[]
     */
    public function getBooks(): array
    {
        return $this->books;
    }

    /**
     * @return Editor[]
     */
    public function getEditors(): array
    {
        return $this->editors;
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }
}
