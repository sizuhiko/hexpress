<?php
namespace Hexpress\Hexpress;

class Character
    private $upcase;
    private $value;

    public function __construct($name, $upcase = false) {
      $this->value = $name;
      $this->upcase = $upcase;
    }

    public static function word() {
      return new Character('\w');
    }

    public static function digit() {
      return new Character('\d');
    }

    public static function space() {
      return new Character('\s');
    }

    public static function any() {
      return new Character('.');
    }

    public static function tab() {
      return new Character('\t');
    }

    public static function newline() {
      return new Character('\n');
    }

    public static function return() {
      return new Character('\r');
    }

    public function value() {
      return $this->upcase? strtoupper($this->value) : $this->value;
    }

    public function toString() {
      return $this->value;
    }
}
