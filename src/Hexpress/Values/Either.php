<?php

namespace Hexpress\Hexpress\Values;

use Hexpress\Hexpress\Values;
use Hexpress\Hexpress\Wrapped;

trait Either
{
    public function either($values)
    {
        return $this->addValue(EitherValue::class, $values);
    }
    public function anyOf($values)
    {
        return $this->either($values);
    }
}

class EitherValue
{
    use Values
    {
        Values::__toString  as valuesToString;
    }
    use Wrapped;

    private $values;
    private $open;
    private $close;

    public function __construct($values)
    {
        $this->values = $values;
        $this->open = '(?:';
        $this->close = ')';
    }

    public function __toString()
    {
        return $this->wrapping($this->valuesToString());
    }

    private function delimiter()
    {
        return '|';
    }
}
