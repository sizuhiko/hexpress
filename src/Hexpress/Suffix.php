<?php
namespace Hexpress\Hexpress;

trait Suffix
{
    public function suffix($value)
    {
        if($value)
        {
            return "{$value}{$this->operator()}";
        }
        else
        {
            return $this->operator();
        }
    }
}
