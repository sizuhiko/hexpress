<?php
namespace Hexpress\Hexpress\Nested;

use Hexpress\Hexpress\Nested;

trait Find {
    public function find($value = NULL) {
        return $this->add_value(NestedFind::class, $value);
    }
    public function capture($value = NULL) {
        return $this->find($value);
    }
}


class NestedFind {
    use Nested;

    public function __construct($value) {
        $this->hexpression = $value;
        $this->open  = "(";
        $this->close = ")";
    }
}
