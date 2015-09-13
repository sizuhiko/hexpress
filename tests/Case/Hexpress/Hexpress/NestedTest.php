<?php
namespace Test\Hexpress\Hexpress;

use Hexpress\Hexpress\Nested;

class ExampleNestedWithDelimiter {
    use Nested;
    
    public function __construct() {
        $this->hexpression = ["foo", "bar"];
        $this->delimiter = ".";
    }
}

class ExampleNestedWithoutDelimiter {
    use Nested;
    
    public function __construct() {
        $this->hexpression = ["foo", "bar"];
    }
}

class ExampleNested {
    use Nested;
    
    public function __construct() {
        $this->hexpression = "foo";
        $this->open = "{";
        $this->close = "}";
    }
}

class NestedTest extends \PHPUnit_Framework_TestCase {
    public function testHexpressionReturnsTheHexpressionItemsWithTheSpecifiedDelimiter() {
        $this->assertEquals("foo.bar", (new ExampleNestedWithDelimiter())->hexpression());
    }

    public function testHexpressionReturnsTheHexpressionItemsWithoutTheSpecifiedDelimiter() {
        $this->assertEquals("foobar", (new ExampleNestedWithoutDelimiter())->hexpression());
    }

    public function testStringReturnsTheHexpressionWrappedInTheOpenAndClose() {
        $this->assertEquals("{foo}", (string)(new ExampleNested()));
    }
}