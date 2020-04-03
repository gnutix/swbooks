<?php

declare(strict_types=1);

namespace Gnutix\Library\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;

final class Owner extends ArrayPopulatedObject
{
    protected string $id;
    protected ?int $nbCopies = null;
    protected ?int $nbReadings = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function getNbCopies(): ?int
    {
        return $this->nbCopies;
    }

    public function getNbReadings(): ?int
    {
        return $this->nbReadings;
    }
}
