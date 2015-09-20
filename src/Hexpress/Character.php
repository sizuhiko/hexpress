<?php

namespace Hexpress\Hexpress;

class Character
{
    private $upcase;
    private $value;

    public function __construct($name, $upcase = false)
    {
        $this->value = $name;
        $this->upcase = $upcase;
    }

    public static function word($upcase = false)
    {
        return new self('\w', $upcase);
    }

    public static function digit($upcase = false)
    {
        return new self('\d', $upcase);
    }

    public static function space($upcase = false)
    {
        return new self('\s', $upcase);
    }

    public static function any($upcase = false)
    {
        return new self('.', $upcase);
    }

    public static function tab($upcase = false)
    {
        return new self('\t', $upcase);
    }

    public static function lf($upcase = false)
    {
        return new self('\n', $upcase);
    }

    public static function cr($upcase = false)
    {
        return new self('\r', $upcase);
    }

    public function value()
    {
        return $this->upcase ? strtoupper($this->value) : $this->value;
    }

    public function __toString()
    {
        return $this->value();
    }
}
