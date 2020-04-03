<?php

declare(strict_types=1);

namespace Gnutix\Library\Helper;

use Webmozart\Assert\Assert;

abstract class ArrayPopulatedObject
{
    public function __construct(array $data = [])
    {
        foreach ($data as $property => $value) {
            Assert::propertyExists($this, $property);
            $this->{$property} = $value;
        }
    }
}
