<?php
namespace Hexpress\Hexpress\Nested;

use Hexpress\Hexpress\Nested;

trait Matching
{
    public function matching($value)
    {
        return $this->add_nested(MatchingValue::class, $value);

    }
    public function like($value)
    {
        return $this->matching($value);
    }
}

class MatchingValue
{
    use Nested;

    private $hexpression;
    private $open;
    private $close;

    public function __construct($hexpression)
    {
        $this->hexpression = $hexpression;
        $this->open = "[";
        $this->close = "]";
    }

    public function join_hexpression()
    {
        return implode("", array_map(function($value) {
            return $this->escape($value);
        }, $this->hexpression));
    }

    private function escape($value)
    {
        return is_string($value)? preg_quote($value) : $value;
    }
}
