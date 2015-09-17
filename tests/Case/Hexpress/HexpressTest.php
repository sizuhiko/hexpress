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

    public function testAllowsYouToChainMethodsToBuildUpRegexPattern()
    {
        $pattern = (new Hexpress())
            ->start("http")
            ->maybe("s")
            ->with("://")
            ->maybe((new Hexpress())->words()->with("."))
            ->find((new Hexpress())->matching(function($hex) {$hex->word()->with("-");})->many())
            ->has(".")
            ->either(["com", "org"])
            ->maybe("/")
            ->ending();
        $this->assertEquals('/^https?\:\/\/(?:(?:\w)+\.)?([\w\-]+)\.(?:com|org)\/?$/', $pattern->toRegExp());
    }

    public function testAdvancedComposureOfMultiplePatterns()
    {
        $protocol = (new Hexpress())->start("http")->maybe("s")->with("://");
        $tld = (new Hexpress())->with(".")->either(["org", "com", "net"]);
        $link = (new Hexpress())->has($protocol)->find(function($hex) {$hex->words();})->including($tld);
        $this->assertEquals("^https?\:\/\/((?:\w)+)\.(?:org|com|net)", $link);
    }

    public function testAlsoEntirelyFeasibleToCompoundTwoOrMorePatternsTogether()
    {
        $protocol = (new Hexpress())->start("http")->maybe("s")->with("://");
        $domain = (new Hexpress())->find(function($hex) {$hex->words();});
        $tld = (new Hexpress())->with(".")->either(["org", "com", "net"]);
        $link =  (new Hexpress())->concat($protocol)->concat($domain)->concat($tld);
        $this->assertEquals("^https?\:\/\/((?:\w)+)\.(?:org|com|net)", $link);
    }
}
