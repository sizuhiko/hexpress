<?php

namespace Hexpress\Hexpress\Value;

use Hexpress\Hexpress\Value;

trait With
{
    public function with($value)
    {
        return $this->add_value(WithValue::class, $value);
    }
    public function has($value)
    {
        return $this->with($value);
    }
    public function including($value)
    {
        return $this->with($value);
    }
}

class WithValue
{
    use Value;
}
