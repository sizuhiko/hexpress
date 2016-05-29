<?php

namespace Hexpress;

use Hexpress\Hexpress\Character;
use Hexpress\Hexpress\Value\With;
use Hexpress\Hexpress\Value\Starting;
use Hexpress\Hexpress\Value\Ending;
use Hexpress\Hexpress\Value\Except;
use Hexpress\Hexpress\Values\Either;
use Hexpress\Hexpress\Values\Range;
use Hexpress\Hexpress\Nested\Find;
use Hexpress\Hexpress\Nested\Matching;
use Hexpress\Hexpress\Noncapture;
use Hexpress\Hexpress\Many;
use Hexpress\Hexpress\One;
use Hexpress\Hexpress\Limit;

class Hexpress implements Noncapture
{
    use With, Starting, Ending, Except;
    use Find, Matching;
    use Many, One, Limit;
    use Either, Range;

    /** */
    private $expressions;

    /**
     *
     */
    public function __construct($hexen = null)
    {
        if ($hexen) {
            if (is_callable($hexen)) {
                $this->expressions = [];
                $hexen($this);
            } else {
                $this->expressions = is_array($hexen) ? $hexen : [$hexen];
            }
        } else {
            $this->expressions = [];
        }
    }

    /**
     * Matches \w.
     */
    public function word()
    {
        $this->add(Character::word());

        return $this;
    }

    /**
     * Matches \d.
     */
    public function digit()
    {
        $this->add(Character::digit());

        return $this;
    }

    /**
     * Matches \s.
     */
    public function space()
    {
        $this->add(Character::space());

        return $this;
    }

    /**
     * Matches .
     */
    public function any()
    {
        $this->add(Character::any());

        return $this;
    }

    /**
     * Matches (?:.)*.
     */
    public function anything()
    {
        $this->many(Character::any(), 0);

        return $this;
    }

    /**
     * Matches (?:.)+.
     */
    public function something()
    {
        $this->many(Character::any(), 1);

        return $this;
    }

    /**
     * Matches \t.
     */
    public function tab()
    {
        $this->add(Character::tab());

        return $this;
    }

    /**
     * Matches (?:(?:\n)|(?:\r\n)).
     */
    public function line()
    {
        $this->either([new Character('(?:\n)'), new Character('(?:\r\n)')]);

        return $this;
    }

    public function words()
    {
        $this->many(Character::word());

        return $this;
    }

    public function digits()
    {
        $this->many(Character::digit());

        return $this;
    }

    public function spaces()
    {
        $this->many(Character::space());

        return $this;
    }

    public function nonword()
    {
        $this->add(Character::word(true));

        return $this;
    }

    public function nondigit()
    {
        $this->add(Character::digit(true));

        return $this;
    }

    public function nonspace()
    {
        $this->add(Character::space(true));

        return $this;
    }

    public function nonwords()
    {
        $this->many(Character::word(true));

        return $this;
    }

    public function nondigits()
    {
        $this->many(Character::digit(true));

        return $this;
    }

    public function nonspaces()
    {
        $this->many(Character::space(true));

        return $this;
    }

    /**
     * This method returns the string version of the regexp.
     */
    public function toRegExp()
    {
        return '/'.$this.'/';
    }

    public function __toString()
    {
        return implode('', array_map(function ($expression) { return "{$expression}"; }, $this->expressions));
    }

    /**
     * Takes an expression and returns the combination of the two expressions
     * in a new Hexpress object.
     */
    public function concat($expression)
    {
        return new self(array_merge($this->expressions, $expression->expressions));
    }

    /**
     * This method takes an hex and adds it to the hexen queue
     * while returning the main object.
     */
    private function add($hex)
    {
        $this->expressions[] = $hex;

        return $this;
    }

    private function addValue($hex, $value)
    {
        $this->add(new $hex(is_callable($value) ? new self($value) : $value));

        return $this;
    }

    private function addNested($hex, $value)
    {
        $this->add(new $hex($value));

        return $this;
    }

    private function addValues($hex, $value, $option)
    {
        $this->add(new $hex(is_callable($value) ? new self($value) : $value, $option));

        return $this;
    }
}
