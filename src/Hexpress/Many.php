<?php

namespace Hexpress\Hexpress;

trait Many
{
    public function many($value = null, $minimum = 1)
    {
        if($minimum > 1) {
            return $this->limit($value, $minimum);
        }
        return $this->addValues(ManyValue::class, $value, $minimum);
    }
}

class ManyValue implements Noncapture
{
    use Value
    {
        Value::__construct as super;
        Value::__toString  as valueToString;
    }
    use Wrapped;
    use Suffix;

    private $minimum;

    public function __construct($value, $minimum = 1)
    {
        $this->super($value);
        $this->minimum = $minimum;
    }

    public function operator()
    {
        switch ($this->minimum) {
            case 0:
                return '*';
            case 1:
                return '+';
        }
    }

    public function __toString()
    {
        return $this->suffix($this->wrapping($this->valueToString()));
    }
}
