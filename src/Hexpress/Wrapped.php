<?php
namespace Hexpress\Hexpress;

trait Wrapped {
    private function open() {
        return $this->open ? $this->open : self::OPEN;
    }

    private function close() {
        return $this->close ? $this->close : self::CLOSE;
    }

    private function wrapping($value) {
        return "{$this->open()}{$value}{$this->close()}";
    }
}