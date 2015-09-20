<?php

namespace Test\Hexpress\Hexpress\Value;

use Hexpress\Hexpress;

class WithTest extends \PHPUnit_Framework_TestCase
{
    public function testAllowsComposingOfMultiplePatterns()
    {
        $pattern1 = (new Hexpress())->starting('foo');
        $pattern2 = (new Hexpress())->ending('bar');
        $pattern3 = (new Hexpress())->including($pattern1)->with('1')->including($pattern2);
        $this->assertEquals('/^foo1bar$/', $pattern3->toRegExp());
    }
}
