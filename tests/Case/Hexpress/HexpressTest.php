<?php
namespace Test\Hexpress;

use Hexpress\Hexpress;

class HexpressTest extends \PHPUnit_Framework_TestCase {
    public function testItTakesChainAndTurnsIntoRegex() {
        $pattern = (new Hexpress())->find("foo");
        $this->assertEquals("/(foo)/", $pattern->toRegExp());
    }

    public function testWordReturnsTheWordMatcher() {
        $this->assertEquals('\w', (new Hexpress())->word());
    }

    public function testDigitReturnsTheDigitMatcher() {
        $this->assertEquals('\d', (new Hexpress())->digit());
    }

    public function testSpaceReturnsTheWhitespaceMatcher() {
        $this->assertEquals('\s', (new Hexpress())->space());
    }

    public function testWordsReturnsTheWordAndMultipleMatchers() {
        $this->assertEquals('(?:\w)+', (new Hexpress())->words());
    }

    public function testDigitsReturnsTheDigitAndMultipleMatchers() {
        $this->assertEquals('(?:\d)+', (new Hexpress())->digits());
    }

    public function testSpecesReturnsTheWhitespaceAndMultipleMatchers() {
        $this->assertEquals('(?:\s)+', (new Hexpress())->spaces());
    }

    public function testNonwordReturnsTheNonwordMatcher() {
        $this->assertEquals('\W', (new Hexpress())->nonword());
    }

    // describe "#nondigit" do
    public function testNondigitReturnsTheNondigitMatcher() {
        $this->assertEquals('\D', (new Hexpress())->nondigit());
    }

    // describe "#nonspace" do
    public function testNonspaceReturnsTheNonwhitespaceMatcher() {
        $this->assertEquals('\S', (new Hexpress())->nonspace());
    }

    public function testNonwordsReturnsTheNonwordAndMultipleMatchers() {
        $this->assertEquals('(?:\W)+', (new Hexpress())->nonwords());
    }

    public function testNondigitsReturnsTheNondigitAndMultipleMatchers() {
        $this->assertEquals('(?:\D)+', (new Hexpress())->nondigits());
    }

    public function testNonspecesReturnsTheNonwhitespaceAndMultipleMatchers() {
        $this->assertEquals('(?:\S)+', (new Hexpress())->nonspaces());
    }

    public function testAnythingReturnsAnyWithZeroOrMorePatternWrappedInNoncapute() {
        $this->assertEquals('(?:.)*', (new Hexpress())->anything());
    }

    public function testConcatReturnsCombinationOfAnyNumberOfExpressions() {
        $pattern1 = (new Hexpress())->with("foo");
        $pattern2 = (new Hexpress())->with("bar");
        $pattern3 = (new Hexpress())->with("bang");
        $pattern4 = $pattern1->concat($pattern2)->concat($pattern3);
        $this->assertEquals('/foobarbang/', $pattern4->toRegExp());
    }

    public function testHexpressShouldBeAbleToMatch() {
        $this->assertEquals(1, preg_match((new Hexpress())->with("foo")->toRegExp(), "foo"));
    }

}
