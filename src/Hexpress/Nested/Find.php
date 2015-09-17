<?php
namespace Hexpress\Hexpress\Nested;

use Hexpress\Hexpress;
use Hexpress\Hexpress\Nested;

trait Find {
    public function find($value = NULL) {
        return is_callable($value)? $this->add_nested(FindValue::class, $value) : $this->add_value(FindValue::class, $value);
    }
    public function capture($value = NULL) {
        return $this->find($value);
    }
}


class FindValue {
    use Nested;

    private $hexpression;
    private $open;
    private $close;

    public function __construct($value) {
        $this->hexpression = is_callable($value)? new Hexpress($value) : $value;
        $this->open  = "(";
        $this->close = ")";
    }
}
