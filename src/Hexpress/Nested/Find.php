<?php

namespace Hexpress\Hexpress\Nested;

use Hexpress\Hexpress;
use Hexpress\Hexpress\Nested;

trait Find
{
    public function find($value = null, $named = false)
    {
        $param = compact('value', 'named');

        return is_callable($value) ? $this->addNested(FindValue::class, $param) : $this->addValue(FindValue::class, $param);
    }
    public function capture($value = null)
    {
        return $this->find($value);
    }
}

class FindValue
{
    use Nested;

    private $hexpression;
    private $open;
    private $close;

    public function __construct($param)
    {
        extract($param);
        $this->hexpression = is_callable($value) ? new Hexpress($value) : $value;
        $this->open = $named ? "(?P<{$named}>" : '(';
        $this->close = ')';
    }
}
