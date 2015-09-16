<?php
namespace Hexpress\Hexpress;

use Hexpress\Hexpress;

trait One
{
    public function one($value = NULL)
    {
        return $this->add_value(OneValue::class, $value);
    }
    public function maybe($value = NULL)
    {
        return $this->one($value);
    }
    public function possibly($value = NULL)
    {
        return $this->one($value);
    }
}

class OneValue implements Noncapture
{
    use Value
    {
        Value::__toString as valueToString;
    }
    use Wrapped;
    use Suffix;

    public function operator()
    {
        return "?";
    }

    public function __toString()
    {
        if($this->value() instanceof Hexpress)
        {
            return $this->suffix($this->wrapping($this->valueToString()));
        }
        else {
            return $this->suffix($this->valueToString());
        }
    }
}
