<?php

namespace Hexpress\Hexpress;

trait Wrapped
{
    private function open()
    {
        return (isset($this->open) && $this->open) ? $this->open : self::OPEN;
    }

    private function close()
    {
        return (isset($this->close) && $this->close) ? $this->close : self::CLOSE;
    }

    private function wrapping($value)
    {
        return "{$this->open()}{$value}{$this->close()}";
    }
}
