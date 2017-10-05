<?php

/**
 * POMO Unit Tests
 * Plural forms class Test
 */

namespace POMO\Tests;

use POMO\Parser\Plural;

class PluralFormsTest extends POMOTestCase
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
        $pluralForms = new Plural($expression);
        $actual = array();
        foreach (array_keys($expected) as $num) {
             $actual[ $num ] = $pluralForms->get($num);
        }
        $this->assertSame($expected, $actual);
    }

    /**
     * Ensures that an exception is thrown when an invalid plural form is encountered.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Unknown symbol "#"
     */
    public function test_invalid_operator()
    {
        $pluralForms = new Plural('n # 2');
    }

    /**
     * Ensures that an exception is thrown when an invalid plural form is encountered.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Unknown operator "&"
     */
    public function test_partial_operator()
    {
        $pluralForms = new Plural('n & 1');
    }

    /**
     * Ensures that an exception is thrown when an invalid plural form is encountered.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Mismatched parentheses
     */
    public function test_mismatched_open_paren()
    {
        $pluralForms = new Plural('((n)');
    }

    /**
     * Ensures that an exception is thrown when an invalid plural form is encountered.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Mismatched parentheses
     */
    public function test_mismatched_close_paren()
    {
        $pluralForms = new Plural('(n))');
    }

    /**
     * Ensures that an exception is thrown when an invalid plural form is encountered.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Missing starting "?" ternary operator
     */
    public function test_missing_ternary_operator()
    {
        $pluralForms = new Plural('n : 2');
    }

    /**
     * Ensures that an exception is thrown when an invalid plural form is encountered.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Unknown operator "?"
     */
    public function test_missing_ternary_else()
    {
        $pluralForms = new Plural('n ? 1');
        $pluralForms->get(1);
    }

    /**
     * Ensures that an exception is thrown when an invalid plural form is encountered.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Too many values remaining on the stack
     */
    public function test_overflow_stack()
    {
        $pluralForms = new Plural('n n');
        $pluralForms->get(1);
    }

    public function test_cache()
    {
        $mock = $this->getMockBuilder('Plural')
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
