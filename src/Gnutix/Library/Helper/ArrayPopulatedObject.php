<?php

namespace Gnutix\Library\Helper;

/**
 * Array populated object
 */
class ArrayPopulatedObject
{
    /**
     * @param array $data
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $data = array())
    {
        foreach ($data as $property => $value) {
            if (!property_exists($this, $property)) {
                throw new \InvalidArgumentException(
                    'The property "'.$property.'" does not exists on object "'.get_called_class().'".'
                );
            }

            $this->$property = $value;
        }
    }
}
