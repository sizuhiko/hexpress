<?php
namespace Hexpress\Hexpress\Value;

use Hexpress\Hexpress\Value;

trait Starting {
    public function starting($value = NULL) {
        return $this->add_value(StartingValue::class, $value);
    }
    public function begins($value) {
        return $this->starting($value);
    }
    public function begin($value) {
        return $this->starting($value);
    }
    public function start($value) {
        return $this->starting($value);
    }
    public function startline($value) {
        return $this->starting($value);
    }
}

class StartingValue {
    use Value;

    public function __toString()
    {
        return "^".$this->value();
    }
}
