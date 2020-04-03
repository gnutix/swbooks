<?php

declare(strict_types=1);

namespace Gnutix\Library\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;

class Book extends ArrayPopulatedObject
{
    protected Category $category;

    /** @var Author[] */
    protected array $authors;

    /** @var Release[] */
    protected array $releases;

    /**
     * @return Author[]
     */
    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @return Release[]
     */
    public function getReleases(): array
    {
        return $this->releases;
    }
}
