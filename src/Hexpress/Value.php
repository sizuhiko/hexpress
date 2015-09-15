<?php
namespace Hexpress\Hexpress;

trait Value {
    private $value;

    public function __construct($value = NULL)
    {
        $this->value = $value;
    }

    public function value()
    {
        return is_string($this->value) ? preg_quote($this->value ? $this->value : "") : $this->value;
    }

    public function __toString()
    {
        return "".$this->value();
    }
}
