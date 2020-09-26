array_dig
=========

recursively fetch nested array keys, deeply, safely, most definitely.

API
===

**array_dig(** mixed `$arr`, mixed `$key1`[, mixed `$key2`[, ..., mixed `$key3`]] **)**

Specify one or more keys to recursively dig in an array.

```php
<?php

$arr = array(
  "a" => array(
    "b" => "c"
  )
);

array_dig($arr, "a", "b"); // "c"
```

-----

**array_dig(** mixed `$arr`, Array `$keys` **)**

Specify an array of keys to recursively dig in an array

```php
<?php

$arr = array(
  "a" => array(
    "b" => "c"
  )
);

$dig = array("a", "b")

array_dig($arr, $dig); // "c"
```

Installation
============

**array_dig** lives on [packagist.org][packagist]

```
$ composer require donut/array_dig
```

Usage
=====

```php
<?php require "vendor/autoload.php";

\Donut\Util\array_dig($arr, $keys);
```

An added benefit of using **array_dig** is that you can immediately access arrays
returned by functions.

```php
<?php

function donut() {
  return array("maple", "jelly", "glazed");
}

// PHP <= 5.3
donut()[1];

// Parse error: syntax error, unexpected '['
```

**array_dig** to the rescue

```php
<?php

array_dig(donut(), 1); // "jelly"
```

Attribution
===========

* nkitsune <nkitsune@mosdef.biz>
* Donut&reg; Club <http://github.com/donutclub>


License
=======

BSD 3-clause

[packagist]: http://packagist.org/packages/donut/array_dig