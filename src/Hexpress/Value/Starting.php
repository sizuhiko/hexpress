<?php
namespace Hexpress\Hexpress\Value;

use Hexpress\Hexpress\Value;

trait Starting {
    public function starting($value) {
        return $this->add_value(ValueStarting::class, $value);
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
}

class ValueStarting {
    use Value;

    public function __toString()
    {
        return "^".$this->value();
    }
}
