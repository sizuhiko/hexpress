<?php

namespace Test\Hexpress\Hexpress;

use Hexpress\Hexpress\Value;

class ExampleValue
{
    use Value;
}

class ValueTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsTheStringGiven()
    {
        $this->assertEquals('foo', (new ExampleValue('foo'))->value());
    }

    public function testReturnsRegexCharactersEscaped()
    {
        $this->assertEquals('fo\\.o', (new ExampleValue('fo.o'))->value());
    }

    public function testReturnsAnEmptyStringIfValueNotGiven()
    {
        $this->assertEquals('', (new ExampleValue(null))->value());
    }
}
