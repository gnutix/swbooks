<?php

declare(strict_types=1);

namespace Gnutix\StarWarsLibrary\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;

final class Era extends ArrayPopulatedObject
{
    protected string $id;
    protected string $name;

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
