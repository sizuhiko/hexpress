<?php

namespace Hexpress\Hexpress;

trait Limit
{
    public function limit($value, $min, $max = 0)
    {
        return $this->add_values(LimitValue::class, $value, [$min, $max]);
    }
}

class LimitValue implements Noncapture
{
    use Value
    {
        Value::__construct as super;
        Value::__toString  as valueToString;
    }
    use Wrapped;
    use Suffix;

    private $min;
    private $max;

    public function __construct($value, $options)
    {
        $this->super($value);
        list($this->min, $this->max) = $options;
    }

    public function operator()
    {
        if ($this->max == 0) {
            return "{{$this->min}}";
        }
        if ($this->max < $this->min) {
            return "{{$this->min},}";
        } else {
            return "{{$this->min},{$this->max}}";
        }
    }

    public function __toString()
    {
        return $this->suffix($this->wrapping($this->valueToString()));
    }
}
