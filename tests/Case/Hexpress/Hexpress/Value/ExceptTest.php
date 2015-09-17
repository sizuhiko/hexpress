<?php
namespace Test\Hexpress\Hexpress\Value;

use Hexpress\Hexpress\Value\Except;

class ExampleValueExcept
{
    use Except;
}

class ExceptTest extends \PHPUnit_Framework_TestCase
{
    public function testToStringReturnsTheNotMarkerFollowedByTheValue()
    {
        $this->assertEquals("^f", new \Hexpress\Hexpress\Value\ExceptValue("f"));
    }
}
