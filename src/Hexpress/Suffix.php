<?php
namespace Hexpress\Hexpress;

trait Suffix
{
    public function suffix($value)
    {
        if($this->value())
        {
            return "{$value}{$this->operator()}";
        }
        else
        {
            return $this->operator();
        }
    }
}
