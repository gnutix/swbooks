<?php

declare(strict_types=1);

namespace Tests\Unit\Library\Helper;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Library\Helper\Mock\ArrayPopulatedObjectMock;

final class ArrayPopulatedObjectTest extends TestCase
{
    /**
     * @dataProvider getObjectData
     */
    public function testObjectInstantiation(array $data, array $expected): void
    {
        $object = new ArrayPopulatedObjectMock($data);

        foreach (get_object_vars($object) as $property => $value) {
            $this->assertEquals($value, $expected[$property]);
        }
    }

    public function getObjectData(): array
    {
        return [
            [
                [],
                [
                    'property1' => null,
                    'property2' => null,
                    'untouchedProperty' => null,
                ],
            ],
            [
                [
                    'property1' => 'test1',
                    'property2' => 'test2',
                ],
                [
                    'property1' => 'test1',
                    'property2' => 'test2',
                    'untouchedProperty' => null,
                ],
            ],
            [
                [
                    'property1' => ['test 1.1', 'test 1.2'],
                    'property2' => new \StdClass(),
                ],
                [
                    'property1' => ['test 1.1', 'test 1.2'],
                    'property2' => new \StdClass(),
                    'untouchedProperty' => null,
                ],
            ],
        ];
    }

    public function testSetUnknownProperty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected the property "unknownProperty" to exist.');

        new ArrayPopulatedObjectMock(
            [
                'property1' => 'test',
                'unknownProperty' => true,
            ]
        );
    }
}
