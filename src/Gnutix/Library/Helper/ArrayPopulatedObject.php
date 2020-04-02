<?php

namespace Gnutix\Library\Helper;

/**
 * Array populated object
 */
abstract class ArrayPopulatedObject
{
    /**
     * @throws \InvalidArgumentException
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $property => $value) {
            if (!property_exists($this, $property)) {
                throw new \InvalidArgumentException(
                    'The property "'.$property.'" does not exists on object "'.static::class.'".'
                );
            }

            $this->{$property} = $value;
        }
    }
}
