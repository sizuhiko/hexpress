<?php
namespace Test\Hexpress\Hexpress;

use Hexpress\Hexpress\Many;

class ExampleWithMany
{
    use Many;
}

class ManyTest extends \PHPUnit_Framework_TestCase {

    public function testOperatorReturnsAsteriskIfMinimumIs0()
    {
        $this->assertEquals("*", (new \Hexpress\Hexpress\ManyValue("", 0))->operator());
    }

    public function testOperatorReturnsPlusIfMinimumIs1()
    {
        $this->assertEquals("+", (new \Hexpress\Hexpress\ManyValue("", 1))->operator());
    }
}
