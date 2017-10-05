<?php

/**
 * POMO Unit Tests
 * PluralForms Test.
 */

namespace POMO\Tests\Parser;

use PHPUnit\Framework\TestCase;
use POMO\Parser\PluralForms;

class PluralFormsTest extends TestCase
{
    public static function simple_provider()
    {
        return array(
            array(
                // Simple equivalence.
                'n != 1',
                array(
                    -1 => 1,
                    0 => 1,
                    1 => 0,
                    2 => 1,
                    5 => 1,
                    10 => 1,
                ),
            ),
            array(
                // Ternary
                'n ? 1 : 2',
                array(
                    -1 => 1,
                    0 => 2,
                    1 => 1,
                    2 => 1,
                ),
            ),
            array(
                // Comparison
                'n > 1 ? 1 : 2',
                array(
                    -2 => 2,
                    -1 => 2,
                    0 => 2,
                    1 => 2,
                    2 => 1,
                    3 => 1,
                ),
            ),
            array(
                'n > 1 ? n > 2 ? 1 : 2 : 3',
                array(
                    -2 => 3,
                    -1 => 3,
                    0 => 3,
                    1 => 3,
                    2 => 2,
                    3 => 1,
                    4 => 1,
                ),
            ),
        );
    }

    /**
     * @dataProvider simple_provider
     */
    public function test_simple($expression, $expected)
    {
        $pluralForms = new PluralForms($expression);
        $actual = array();
        foreach (array_keys($expected) as $num) {
            $actual[$num] = $pluralForms->get($num);
        }
        $this->assertSame($expected, $actual);
    }

    public function data_exceptions()
    {
        return array(
            array(
                'n # 2',              // Invalid expression to parse
                'Unknown symbol "#"', // Expected exception message
                false,                // Whether to call the get() method or not
            ),
            array(
                'n & 1',
                'Unknown operator "&"',
                false,
            ),
            array(
                '((n)',
                'Mismatched parentheses',
                false,
            ),
            array(
                '(n))',
                'Mismatched parentheses',
                false,
            ),
            array(
                'n : 2',
                'Missing starting "?" ternary operator',
                false,
            ),
            array(
                'n ? 1',
                'Unknown operator "?"',
                true,
            ),
            array(
                'n n',
                'Too many values remaining on the stack',
                true,
            ),
        );
    }

    /**
     * Ensures that an exception is thrown when an invalid plural form is encountered.
     *
     * The `@expectedException \Exception` notation for PHPUnit cannot be used because expecting an
     * exception of type `Exception` is not supported before PHPUnit 3.7. The CI tests for PHP 5.2
     * run on PHPUnit 3.6.
     *
     * @dataProvider data_exceptions
     */
    public function test_exceptions($expression, $expected_exception, $call_get)
    {
        try {
            $pluralForms = new PluralForms($expression);
            if ($call_get) {
                $pluralForms->get(1);
            }
        } catch (\Exception $e) {
            $this->assertEquals($expected_exception, $e->getMessage());

            return;
        }

        $this->fail('Expected exception was not thrown.');
    }

    public function test_cache()
    {
        $mock = $this->getMockBuilder('\POMO\Parser\PluralForms')
            ->setMethods(array('execute'))
            ->setConstructorArgs(array('n != 1'))
            ->getMock();

        $mock->expects($this->once())
            ->method('execute')
            ->with($this->identicalTo(2))
            ->will($this->returnValue(1));

        $first = $mock->get(2);
        $second = $mock->get(2);
        $this->assertEquals($first, $second);
    }
}
