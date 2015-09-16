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
        if(empty($this->value)) {
            return "";
        }
        return is_string($this->value) ? preg_quote($this->value) : $this->value;
    }

    public function __toString()
    {
        return "".$this->value();
    }
}
