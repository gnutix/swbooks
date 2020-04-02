<?php

namespace Gnutix\Library\Tests\Helper\Unit;

use Gnutix\Library\Tests\Unit\Helper\Mock\ArrayPopulatedObjectMock;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the ArrayPopulatedObject class
 *
 * @group unit
 */
class ArrayPopulatedObjectTest extends TestCase
{
    /**
     * @param array $data
     * @param array $expected
     *
     * @dataProvider getObjectData
     */
    public function testObjectInstantiation(array $data, array $expected)
    {
        $object = new ArrayPopulatedObjectMock($data);

        foreach (get_object_vars($object) as $property => $value) {
            $this->assertEquals($value, $expected[$property]);
        }
    }

    /**
     * @return array
     */
    public function getObjectData()
    {
        return array(
            array(
                array(),
                array(
                    'property1' => null,
                    'property2' => null,
                    'untouchedProperty' => null,
                )
            ),
            array(
                array(
                    'property1' => 'test1',
                    'property2' => 'test2',
                ),
                array(
                    'property1' => 'test1',
                    'property2' => 'test2',
                    'untouchedProperty' => null,
                ),
            ),
            array(
                array(
                    'property1' => array('test 1.1', 'test 1.2'),
                    'property2' => new \StdClass,
                ),
                array(
                    'property1' => array('test 1.1', 'test 1.2'),
                    'property2' => new \StdClass,
                    'untouchedProperty' => null,
                ),
            ),
        );
    }

    public function testSetUnknownProperty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The property "unknownProperty" does not exists on object');

        new ArrayPopulatedObjectMock(
            array(
                'property1' => 'test',
                'unknownProperty' => true,
            )
        );
    }
}
