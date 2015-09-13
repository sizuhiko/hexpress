<?php
namespace Hexpress;

use Hexpress\Hexpress\Character;
use Hexpress\Hexpress\Value\With;
use Hexpress\Hexpress\Value\Starting;
use Hexpress\Hexpress\Value\Ending;
use Hexpress\Hexpress\Nested\Find;

class Hexpress {
    const OPEN = "(?:";
    const CLOSE = ")";

    use With, Starting, Ending;
    use Find;

    /** */
    private $hexen;
    /** */
    private $expressions;

    /**
     *
     */
    public function __construct($hexen = NULL) {
        $this->hexen = $hexen;
        $this->expressions = [];
    }

    /**
     * Matches \w
     */
    public function word() {
        $this->add(Character::word());
        return $this;
    }

    /**
     * Matches \d
     */
    public function digit() {
        $this->add(Character::digit());
        return $this;
    }

    /**
     * Matches \s
     */
    public function space() {
        $this->add(Character::space());
        return $this;
    }

    /**
     * Matches .
     */
    public function any() {
        $this->add(Character::any());
        return $this;
    }

    /**
     * Matches (?:.)*
     */
    public function anything() {
        $this->many(Character::any(), 0);
        return $this;
    }

    /**
     * Matches (?:.)+
     */
    public function something() {
        $this->many(Character::any(), 1);
        return $this;
    }

    /**
     * Matches \t
     */
    public function tab() {
        $this->add(Character::tab());
        return $this;
    }

    /**
     * Matches (?:(?:\n)|(?:\r\n))
     */
    public function line() {
        $this->either(new Character('(?:\n)'), new Character('(?:\r\n)'));
        return $this;
    }

//  CHARACTERS = [:word, :digit, :space]
    public function words() {
        $this->many(Character::word());
        return $this;
    }

    public function digits() {
        $this->many(Character::digit());
        return $this;
    }

    public function spaces() {
        $this->many(Character::space());
        return $this;
    }

/*
    CHARACTERS.each do |character|
    define_method(character) do
      add(Character.new(character))
    end

    define_method("non#{character}") do
      add(Character.new(character, true))
    end

    define_method("#{character}s") do
      many(Character.new(character))
    end

    define_method("non#{character}s") do
      many(Character.new(character, true))
    end
    end
*/

    /**
     * This method returns the string version of the regexp.
     */
    public function toRegExp() {
        return '/'.$this.'/';
    }

    public function __toString() {
        return implode('', array_map(function($expression) { return "{$expression}"; }, $this->expressions));
    }

    /**
     * Takes an expression and returns the combination of the two expressions
     * in a new Hexpress object.
     */
    public function concat($expression) {
        return new Hexpress(array_merge($this->expressions, $expression->expressions));
    }

    /**
     * This method takes an hex and adds it to the hexen queue
     * while returning the main object.
     */
    private function add($hex) {
        $this->expressions[] = $hex;
        return $this;
    }

    private function add_value($hex, $value) {
        $this->add(new $hex($value));
        return $this;
    }

    private function add_nested($hex) {
        $this->add(new $hex());
        return $this;
    }
}

/*
require_relative "hexpress/character"
require_relative "hexpress/version"
require_relative "hexpress/value"
require_relative "hexpress/suffix"
require_relative "hexpress/prefix"
require_relative "hexpress/wrapped"
require_relative "hexpress/noncapture"
require_relative "hexpress/many"

if defined?(Rails)
  require_relative "hexpress/main"
end
*/