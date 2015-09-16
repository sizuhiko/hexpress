<?php
namespace Test\Hexpress\Hexpress\Nested;

use Hexpress\Hexpress\Nested\Find;
use Hexpress\Hexpress\Character;

class ExampleNestedFind
{
    use Find;
}

class FindTest extends \PHPUnit_Framework_TestCase
{

    public function testToStringReturnsCaptureOfTheHexpression()
    {
        $this->assertEquals('(\w)', new \Hexpress\Hexpress\Nested\FindValue(Character::word()));
    }

    public function testToStringReturnsCaptureOfTheString()
    {
        $this->assertEquals('(foo)', new \Hexpress\Hexpress\Nested\FindValue("foo"));
    }
}
