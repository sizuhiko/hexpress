<?php

namespace Hexpress\Hexpress;

trait Nested
{
    use Wrapped;

    public function delimiter()
    {
        return isset($this->delimiter) ? $this->delimiter : '';
    }

    public function hexpression()
    {
        return $this->joinable() ? $this->joinHexpression() : $this->hexpression;
    }

    public function __toString()
    {
        return $this->wrapping($this->hexpression());
    }

    private function joinHexpression()
    {
        return implode($this->delimiter(), $this->hexpression);
    }

    private function joinable()
    {
        return is_array($this->hexpression);
    }
}
