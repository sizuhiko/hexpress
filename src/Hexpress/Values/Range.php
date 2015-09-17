<?php
namespace Hexpress\Hexpress\Values;

use Hexpress\Hexpress\Values;

trait Range
{
    public function letter()
    {
        $this->lower();
        $lower = $this->pop_value();
        $this->upper();
        $upper = $this->pop_value();
        return $lower.$upper;
    }

    public function lower($value = false)
    {
        $value = empty($value)? range("a", "z") : $value;
        return $this->add_value(RangeValue::class, $value);
    }

    public function upper($value = false)
    {
        $value = empty($value)? range("A", "Z") : $value;
        return $this->add_value(RangeValue::class, $value);
    }

    public function number($value = false)
    {
        $value = empty($value)? range("0", "9") : $value;
        return $this->add_value(RangeValue::class, $value);
    }
}

class RangeValue
{
    use Values;

    private $values;

    public function __construct($range)
    {
        $this->values = [reset($range), end($range)];
    }

    private function delimiter()
    {
        return "-";
    }
}
