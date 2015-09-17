<?php
namespace Test\Hexpress\Hexpress;

use Hexpress\Hexpress;
use Hexpress\Hexpress\Character;
use Hexpress\Hexpress\One;

class ExampleWithOne
{
    use One;
}

class OneTest extends \PHPUnit_Framework_TestCase {
    public function testMaybeReturnsQuestionSuffix()
    {
        $this->assertEquals('/(?:(?:\w)+)?/', (new Hexpress())->maybe(function($hex){$hex->words();})->toRegExp());
    }

    public function testMaybeReturnsOnlyQuestionIfNoValueGiven()
    {
        $this->assertEquals('/foo?/', (new Hexpress())->with("foo")->maybe()->toRegExp());
    }

    public function testOperatorReturnsTheQuestionOperator()
    {
        $this->assertEquals("?", (new \Hexpress\Hexpress\OneValue("foo"))->operator());
    }
}
