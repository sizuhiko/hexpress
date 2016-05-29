<?php

namespace Test\Hexpress\Hexpress;

use Hexpress\Hexpress\Many;
use Hexpress\Hexpress\Limit;

class ExampleWithMany
{
    use Many;
    use Limit;
}

class ManyTest extends \PHPUnit_Framework_TestCase
{
    public function testOperatorReturnsAsteriskIfMinimumIs0()
    {
        $this->assertEquals('*', (new \Hexpress\Hexpress\ManyValue('', 0))->operator());
    }

    public function testOperatorReturnsPlusIfMinimumIs1()
    {
        $this->assertEquals('+', (new \Hexpress\Hexpress\ManyValue('', 1))->operator());
    }

    public function testOperatorReturnsLimitIfMinimumOver1()
    {
        $this->assertEquals('/(?:(?:\w)+){2}/', (new \Hexpress\Hexpress())->many(function ($hex) {$hex->words();}, 2)->toRegExp());
    }
}
