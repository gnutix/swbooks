<?php

declare(strict_types=1);

namespace Tests\Unit\Library\Helper\Mock;

use Gnutix\Library\Helper\ArrayPopulatedObject;

final class ArrayPopulatedObjectMock extends ArrayPopulatedObject
{
    /**
     * @var mixed
     */
    public $property1;

    /**
     * @var mixed
     */
    public $untouchedProperty;

    /**
     * @var mixed
     */
    public $property2;
}
