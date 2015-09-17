<?php
namespace Test\Hexpress\Hexpress\Values;

use Hexpress\Hexpress;
use Hexpress\Hexpress\Values\Range;

class ExampleValueRange
{
    use Range;
}

class RangeTest extends \PHPUnit_Framework_TestCase
{
    public function testLowerReturnsTheLowerCharacterMatcher()
    {
        $this->assertEquals("a-z", (new Hexpress())->lower());
    }

    public function testUpperReturnsTheUpperCharacterMatcher()
    {
        $this->assertEquals("A-Z", (new Hexpress())->upper());
    }

    public function testLetterReturnsTheUpperAndLowerCharacterMatchers()
    {
        $this->assertEquals("a-zA-Z", (new Hexpress())->letter());
    }

    public function testNumberReturnsTheNumberMatcher()
    {
        $this->assertEquals("0-9", (new Hexpress())->number());
    }

    public function testToStringReturnsTwoValuesJoinedByRangeMatcher()
    {
        $this->assertEquals("0-9", (new \Hexpress\Hexpress\Values\RangeValue(range(0, 9))));
    }
}
