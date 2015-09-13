<?php
namespace Hexpress\Hexpress\Value;

use Hexpress\Hexpress\Value;

trait With {
    public function with($value) {
        return $this->add_value(ValueWith::class, $value);
    }
    public function has($value) {
        return $this->with($value);
    }
    public function including($value) {
        return $this->with($value);
    }
}

class ValueWith {
    use Value;
}
