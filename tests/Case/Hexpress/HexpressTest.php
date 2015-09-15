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

    // describe "#+" do
    // it "returns a combination of any number of expressions" do
    //   pattern1 = Hexpress.new.with("foo")
    //   pattern2 = Hexpress.new.with("bar")
    //   pattern3 = Hexpress.new.with("bang")
    //   pattern4 = pattern1 + pattern2 + pattern3
    //   expect(pattern4.to_r).to eq(/foobarbang/)
    // end
    // end

    // describe "#to_regexp" do
    // it "should return a Regexp object" do
    //   expect(Hexpress.new.to_regexp).to be_a(Regexp)
    // end
    // end

    // describe "acts as a Regexp" do
    // it "should work for Regexp#try_convert" do
    //   expect(Regexp.try_convert(Hexpress.new)).not_to be_nil
    // end

    // it "should be able to match" do
    //   expect(Hexpress.new.with("foo").match("foo")).to_not be_nil
    // end

    // it "should match using #=~" do
    //   expect(Hexpress.new.with("foo") =~ "foo").to eq(0)
    // end

    // it "should match using ===" do
    //   expect(Hexpress.new.with("foo") === "foo").to be_true
    // end

    // it "should be able to be matched by strings using =~" do
    //   expect("foo" =~ Hexpress.new.with("foo")).to be_true
    // end
    // end
}
