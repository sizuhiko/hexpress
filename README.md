# hexpress
hexpress is a PHP library that human way to define regular expressions

hexpress is ported from Ruby's [hexpress](https://github.com/krainboltgreene/hexpress).

The hexpress is another take at the concept of ["Verbal Hexpressions"]().

hexpress requires PHP >= 5.5

## Using

``` php
use Hexpress\Hexpress;

$pattern = (new Hexpress())
    ->start("http")
    ->maybe("s")
    ->with("://")
    ->maybe((new Hexpress())->words()->with("."))
    ->find((new Hexpress())->matching(new Hexpress([Character::word(), "-"]))->multiple())
    ->has(".")
    ->either(["com", "org"])
    ->maybe("/")
    ->ending();
```

After `use Hexpress\Hexpress` you'll have access to the Hexpress class, which allows you to chain methods to build up a regex pattern.

You can see this pattern by calling either `Hexpress#__toString()` or `Hexpress#toRegExp`:

``` php
(string)pattern     #=> "^http(?:s)?://(?:(?:\\w)+\\.)?([\\w\\-]+)\\.(?:com|org)(?:/)?$"
pattern->toRegExp() #=> "/^http(?:s)?:\/\/(?:(?:\\w)+\\.)?([\\w\\-]+)\\.(?:com|org)(?:\/)?$/"
```

You can even do advanced composure of multiple patterns:

``` php
$protocol = (new Hexpress())->start("http")->maybe("s")->with("://");
$tld = (new Hexpress())->then(".")->either(["org", "com", "net"]);
$link = (new Hexpress())->has($protocol)->find((new Hexpress())->words())->including($tld);
```

It's also entirely feasible to compound two or more patterns together:

``` php
$protocol = (new Hexpress())->start("http")->maybe("s")->with("://");
$domain = (new Hexpress())->find((new Hexpress())->words());
$tld = (new Hexpress())->then(".")->either(["org", "com", "net"]);
$link =  (new Hexpress())->concat(protocol)->concat(domain)->concat(tld);
```

Hexpressions are very flexible.

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

