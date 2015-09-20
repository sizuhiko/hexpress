<?php
namespace Test\Hexpress\Hexpress\Nested;

use Hexpress\Hexpress\Nested\Find;

class ExampleNestedFind
{
    use Find;
}

class FindTest extends \PHPUnit_Framework_TestCase
{

    public function testToStringReturnsCaptureOfTheHexpression()
    {
        $this->assertEquals('(\w)', new \Hexpress\Hexpress\Nested\FindValue(["value"=> function($hex) {$hex->word();}]));
    }

    public function testToStringReturnsCaptureOfTheString()
    {
        $this->assertEquals('(foo)', new \Hexpress\Hexpress\Nested\FindValue(["value"=> "foo"]));
    }

    public function testToStringReturnsCaptureOfTheNamedString()
    {
        $this->assertEquals('(?P<bar>foo)', new \Hexpress\Hexpress\Nested\FindValue(["value"=> "foo", "named"=> "bar"]));
    }
}
