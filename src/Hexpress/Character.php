<?php
namespace Hexpress\Hexpress;

class Character {
    private $upcase;
    private $value;

    public function __construct($name, $upcase = false) {
      $this->value = $name;
      $this->upcase = $upcase;
    }

    public static function word($upcase = false) {
      return new Character('\w', $upcase);
    }

    public static function digit($upcase = false) {
      return new Character('\d', $upcase);
    }

    public static function space($upcase = false) {
      return new Character('\s', $upcase);
    }

    public static function any($upcase = false) {
      return new Character('.', $upcase);
    }

    public static function tab($upcase = false) {
      return new Character('\t', $upcase);
    }

    public static function lf($upcase = false) {
      return new Character('\n', $upcase);
    }

    public static function cr($upcase = false) {
      return new Character('\r', $upcase);
    }

    public function value() {
      return $this->upcase? strtoupper($this->value) : $this->value;
    }

    public function __toString() {
      return $this->value();
    }
}
