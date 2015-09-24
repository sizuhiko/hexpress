<?php

namespace Hexpress\Hexpress\Value;

use Hexpress\Hexpress\Value;

trait Except
{
    public function except($value)
    {
        return $this->addValue(ExceptValue::class, $value);
    }
    public function excluding($value)
    {
        return $this->except($value);
    }
    public function exclude($value)
    {
        return $this->except($value);
    }
    public function without($value)
    {
        return $this->except($value);
    }
}

class ExceptValue
{
    use Value;

    public function __toString()
    {
        return "^{$this->value()}";
    }
}
