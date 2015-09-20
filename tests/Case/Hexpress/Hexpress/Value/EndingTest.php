<?php

namespace Test\Hexpress\Hexpress\Value;

use Hexpress\Hexpress\Value\Ending;

class ExampleValueEnding
{
    use Ending;
}

class EndingTest extends \PHPUnit_Framework_TestCase
{
    public function testToStringReturnsTheEndOfStringPattern()
    {
        $this->assertEquals('$', new \Hexpress\Hexpress\Value\EndingValue());
    }

    public function testToStringHasTheGivenStringBeforeTheEndOfStringPattern()
    {
        $this->assertEquals('foo$', new \Hexpress\Hexpress\Value\EndingValue('foo'));
    }
}
