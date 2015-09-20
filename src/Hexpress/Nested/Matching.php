<?php

namespace Hexpress\Hexpress\Nested;

use Hexpress\Hexpress;
use Hexpress\Hexpress\Nested;

trait Matching
{
    public function matching($callback)
    {
        return $this->add_nested(MatchingValue::class, $callback);
    }
    public function like($callback)
    {
        return $this->matching($callback);
    }
}

class MatchingValue
{
    use Nested;

    private $hexpression;
    private $open;
    private $close;

    public function __construct($callback)
    {
        $this->hexpression = new Hexpress($callback);
        $this->open = '[';
        $this->close = ']';
    }

    public function join_hexpression()
    {
        return implode('', array_map(function ($value) {
            return $this->escape($value);
        }, $this->hexpression));
    }

    private function escape($value)
    {
        return is_string($value) ? preg_quote($value, '/') : $value;
    }
}
