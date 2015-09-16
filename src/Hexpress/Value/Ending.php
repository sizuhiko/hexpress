<?php
namespace Hexpress\Hexpress\Value;

use Hexpress\Hexpress\Value;

trait Ending {
    public function ending($value) {
        return $this->add_value(EndingValue::class, $value);
    }
    public function end($value) {
        return $this->ending($value);
    }
    public function endline($value) {
        return $this->ending($value);
    }
}

class EndingValue {
    use Value;

    public function __toString()
    {
        return "".$this->value()."$";
    }
}
