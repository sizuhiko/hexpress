<?php
namespace Hexpress\Hexpress;

trait Values
{
    public function values()
    {
        return array_map(function($value) {
            return is_string($value)? preg_quote($value) : $value;
        }, $this->values);
    }

    public function __toString()
    {
        return implode($this->delimiter(), $this->values());
    }
}
