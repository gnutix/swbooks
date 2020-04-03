<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Webmozart\Assert\Assert;

abstract class PhpUnitSimpleTestCase extends TestCase
{
    /** @var object */
    protected $instance;

    /**
     * @param mixed $expected
     *
     * @dataProvider getSimpleMethodsData
     */
    public function testSimpleMethods(string $method, array $arguments, $expected): void
    {
        Assert::object($this->instance);

        $this->setUpBeforeSimpleMethodsTests();
        $result = $this->instance->{$method}(...$arguments);

        $this->assertSame($expected, $result);
    }

    /**
     * Hook to be able to execute some code before the execution of the test
     */
    public function setUpBeforeSimpleMethodsTests(): void
    {
    }

    abstract public function getSimpleMethodsData(): array;
}
