<?php

declare(strict_types=1);

namespace Gnutix\Library\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;

/**
 * Books Editor
 */
final class Editor extends ArrayPopulatedObject
{
    protected string $id;
    protected string $name;
    protected ?string $preferredLanguage = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPreferredLanguage(): ?string
    {
        return $this->preferredLanguage;
    }
}
