[![Build Status](https://travis-ci.org/sizuhiko/hexpress.svg?branch=master)](https://travis-ci.org/sizuhiko/hexpress) [![Coverage Status](https://coveralls.io/repos/sizuhiko/hexpress/badge.svg?branch=master&service=github)](https://coveralls.io/github/sizuhiko/hexpress?branch=master) [![Latest Stable Version](https://poser.pugx.org/sizuhiko/hexpress/v/stable)](https://packagist.org/packages/sizuhiko/hexpress) [![Total Downloads](https://poser.pugx.org/sizuhiko/hexpress/downloads)](https://packagist.org/packages/sizuhiko/hexpress) [![Latest Unstable Version](https://poser.pugx.org/sizuhiko/hexpress/v/unstable)](https://packagist.org/packages/sizuhiko/hexpress) [![License](https://poser.pugx.org/sizuhiko/hexpress/license)](https://packagist.org/packages/sizuhiko/hexpress)

# hexpress
hexpress is a PHP library that human way to define regular expressions

hexpress is ported from Ruby's [hexpress](https://github.com/krainboltgreene/hexpress).

The hexpress is another take at the concept of ["Verbal Hexpressions"](http://verbalexpressions.github.io/).

hexpress requires PHP >= 5.5

## Using

``` php
use Hexpress\Hexpress;

$pattern = (new Hexpress())
    ->start("http")
    ->maybe("s")
    ->with("://")
    ->maybe(function($hex) { $hex->words()->with("."); })
    ->find(function($hex) { $hex->matching(function($hex) {$hex->word()->with("-");})->many(); })
    ->has(".")
    ->either(["com", "org"])
    ->maybe("/")
    ->ending();
```

After `use Hexpress\Hexpress` you'll have access to the Hexpress class, which allows you to chain methods to build up a regex pattern.

You can see this pattern by calling either `Hexpress#__toString()` or `Hexpress#toRegExp`:

``` php
echo $pattern;             #=> "^https?\:\/\/(?:(?:\w)+\.)?([\w\-]+)\.(?:com|org)\/?$"
echo $pattern->toRegExp(); #=> "/^https?\:\/\/(?:(?:\w)+\.)?([\w\-]+)\.(?:com|org)\/?$/"
```

You can even do advanced composure of multiple patterns:

``` php
$protocol = (new Hexpress())->start("http")->maybe("s")->with("://");
$tld = (new Hexpress())->with(".")->either(["org", "com", "net"]);
$link = (new Hexpress())->has($protocol)->find(function($hex) {$hex->words();})->including($tld);

echo $link; #=> "^https?\:\/\/((?:\w)+)\.(?:org|com|net)"
```

Hexpressions are very flexible.

## TODO

- PHP Document and API reference
- lookahead assertions
- lookbehind assertions
- back reference
- conditional subpattern
- once-only subpattern
- Internal option setting

## Installing

Add this line to your application's composer.json:

``` json
    "require": {
        "sizuhiko/hexpress": ">=1.0"
    },
```

And then execute:

    $ composer install

Or install it yourself as:

    $ composer require "sizuhiko/hexpress:>=1.0"


## Contributing to this Library

Please feel free to contribute to the library with new issues, requests, unit tests and code fixes or new features.
If you want to contribute some code, create a feature branch from develop, and send us your pull request.

