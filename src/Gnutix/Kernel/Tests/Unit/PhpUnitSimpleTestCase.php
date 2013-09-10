<?php

namespace Gnutix\Kernel\Tests\Unit;

/**
 * PhpUnit Simple TestCase
 */
abstract class PhpUnitSimpleTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var object|\PHPUnit_Framework_MockObject_MockObject */
    protected $instance;

    /**
     * @param string      $method    The method to test
     * @param mixed|array $arguments The input argument (mixed or multiple arguments as array)
     * @param string      $expected  The expected result
     *
     * @dataProvider getSimpleMethodsData
     * @throws \UnexpectedValueException
     */
    public function testSimpleMethods($method, $arguments, $expected)
    {
        if (!is_object($this->instance)) {
            throw new \UnexpectedValueException('You must populate the $instance property before using '.__METHOD__);
        }

        $this->setUpBeforeSimpleMethodsTests();

        if ($this->instance instanceof \PHPUnit_Framework_MockObject_MockObject) {
            $this->createResult();

        } else {

            // Have a condition for performance as call_user_func_array is slow
            if (is_array($arguments)) {
                $result = call_user_func_array(array($this->instance, $method), $arguments);
            } else {
                $result = $this->instance->$method($arguments);
            }
        }

        $this->assertEquals($expected, $result);
    }

    /**
     * Hook to be able to execute some code before the execution of the test
     */
    public function setUpBeforeSimpleMethodsTests()
    {
    }

    /**
     * @return array
     */
    abstract public function getSimpleMethodsData();
}
