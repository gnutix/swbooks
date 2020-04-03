<?php

declare(strict_types=1);

namespace Gnutix\Library\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;

final class Author extends ArrayPopulatedObject
{
    protected string $id;
    protected string $name;

    public function getId(): string
    {
        /* Hack to create an ID when there's none. Useful when dumping a library.
        if (null === $this->id && !empty($this->name)) {
            return strtolower(str_replace(array(' ', '-', '.'), array('_', '_', ''), $this->name));
        }
        */

        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
