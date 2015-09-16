<?php
namespace Test\Hexpress\Hexpress\Value;

use Hexpress\Hexpress\Value\Starting;

class ExampleValueStarting
{
    use Starting;
}

class StartingTest extends \PHPUnit_Framework_TestCase
{
    public function testToStringReturnsTheStartOfStringPattern()
    {
        $this->assertEquals('^', new \Hexpress\Hexpress\Value\StartingValue());
    }

    public function testToStringHasTheGivenStringAfterTheStartOfStringPattern()
    {
        $this->assertEquals('^foo', new \Hexpress\Hexpress\Value\StartingValue('foo'));
    }
}
