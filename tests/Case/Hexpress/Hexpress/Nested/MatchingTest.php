<?php
namespace Test\Hexpress\Hexpress\Nested;

use Hexpress\Hexpress\Nested\Matching;
use Hexpress\Hexpress\Character;

class ExampleNestedMatching
{
    use Matching;
}

class MatchingTest extends \PHPUnit_Framework_TestCase
{
    public function testHexpressionEscapesStrings()
    {
        $this->assertEquals('[\w\-]', new \Hexpress\Hexpress\Nested\MatchingValue([Character::word(), "-"]));
    }

    public function testToStringReturnsTheInstanceOfHexpressionsAndWraps()
    {
        $this->assertEquals('[\w]', new \Hexpress\Hexpress\Nested\MatchingValue(Character::word()));
    }
}
