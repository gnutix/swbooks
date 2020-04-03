<?php

declare(strict_types=1);

namespace Gnutix\Library\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;

final class Series extends ArrayPopulatedObject
{
    protected ?string $id;
    protected ?string $title;
    protected ?int $bookId;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getBookId(): ?int
    {
        return $this->bookId;
    }
}
