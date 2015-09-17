<?php
namespace Test\Hexpress\Hexpress\Values;

use Hexpress\Hexpress\Values\Either;

class ExampleValueEither
{
    use Either;
}

class EitherTest extends \PHPUnit_Framework_TestCase
{
    public function testToStringReturnsItemsDelimitedByOrMatcherInsideNonCaptureGroup()
    {
        $this->assertEquals("(?:foo|bar)", new \Hexpress\Hexpress\Values\EitherValue(["foo", "bar"]));
    }
}
