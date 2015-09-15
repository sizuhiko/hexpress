<?php
namespace Hexpress\Hexpress;

trait Values
{
    public function values()
    {
        return array_map(function($value) {
            return preg_quote($value);
        }, $this->values);
    }

    public function __toString()
    {
        return implode($this->delimiter(), $this->values());
    }
}
