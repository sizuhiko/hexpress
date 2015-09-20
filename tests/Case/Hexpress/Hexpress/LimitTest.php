<?php

namespace Test\Hexpress\Hexpress;

use Hexpress\Hexpress;
use Hexpress\Hexpress\Limit;

class ExampleWithLimit
{
    use Limit;
}

class LimitTest extends \PHPUnit_Framework_TestCase
{
    public function testLimitReturnsOnlyMinSuffixIfNoMaxValueGiven()
    {
        $this->assertEquals('/(?:(?:\w)+){2}/', (new Hexpress())->limit(function ($hex) {$hex->words();}, 2)->toRegExp());
    }

    public function testLimitReturnsMinAndCommaIfMinGraterThanMax()
    {
        $this->assertEquals('/(?:(?:\w)+){2,}/', (new Hexpress())->limit(function ($hex) {$hex->words();}, 2, 1)->toRegExp());
    }

    public function testLimitReturnsLimitRange()
    {
        $this->assertEquals('/(?:(?:\w)+){1,5}/', (new Hexpress())->limit(function ($hex) {$hex->words();}, 1, 5)->toRegExp());
    }
}
