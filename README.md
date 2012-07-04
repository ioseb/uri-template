URI Template PHP Extension
============

PHP extension implementation of RFC-6570 in C - http://tools.ietf.org/html/rfc6570

Basic usage
-----------

Substituting query parameters:


```php
<?php
$data = array(
	"query"  => "mycelium",
	"number" => 100
);

$uri = uri_template('http://www.example.com/foo{?query,number}', $data);
?>
```

This will result to following URI:

	http://www.example.com/foo?query=mycelium&number=100

Detailed examples
-----------------

All following subsections will rely on the data array shown below:

```php
<?php
$data = array(
  'count'      => array("one", "two", "three"),
  'dom'        => array("example", "com"),
  'dub'        => "me/too",
  'hello'      => "Hello World!",
  'half'       => "50%",
  'var'        => "value",
  'who'        => "fred",
  'base'       => "http://example.com/home/",
  'path'       => "/foo/bar",
  'list'       => array("red", "green", "blue"),
  'keys'       => array(
    "semi"  => ";",
    "dot"   => ".",
    "comma" => ",",
  ),
  'v'          => "6",
  'x'          => "1024",
  'y'          => "768",
  'empty'      => "",
  'empty_keys' => array(),
  'undef'      => null,
);
?>
```


Variable expansion
------------------

For more details see [corresponding spec](http://tools.ietf.org/html/rfc6570#section-3.2.1).

```php
<?php
$templates = array(
  '{count}',
  '{count*}',
  '{/count}',
  '{/count*}',
  '{;count}',
  '{;count*}',
  '{?count}',
  '{?count*}',
  '{&count*}',
);

$uris = array();

foreach ($templates as $template) {
  $uris[$template] = uri_template($template, $data);
}

var_export($uris);
?>
```

This will result to following URI array:

```php
<?php
array (
  '{count}'   => 'one,two,three',
  '{count*}'  => 'one,two,three',
  '{/count}'  => '/one,two,three',
  '{/count*}' => '/one/two/three',
  '{;count}'  => ';count=one,two,three',
  '{;count*}' => ';count=one;count=two;count=three',
  '{?count}'  => '?count=one,two,three',
  '{?count*}' => '?count=one&count=two&count=three',
  '{&count*}' => '&count=one&count=two&count=three',
)
?>
```

Simple String Expansion: {var}
------------------------------

For more details see [corresponding spec](http://tools.ietf.org/html/rfc6570#section-3.2.2).

```php
<?php
$templates = array(
  "{var}",
  "{hello}",
  "{half}",
  "O{empty}X",
  "O{undef}X",
  "{x,y}",
  "{x,hello,y}",
  "?{x,empty}",
  "?{x,undef}",
  "?{undef,y}",
  "{var:3}",
  "{var:30}",
  "{list}",
  "{list*}",
  "{keys}",
  "{keys*}"
);

$uris = array();

foreach ($templates as $template) {
  $uris[$template] = uri_template($template, $data);
}

var_export($uris);
?>
```

This will result to following URI array:

```php
<?php
array (
  '{var}'       => 'value',
  '{hello}'     => 'Hello%20World%21',
  '{half}'      => '50%25',
  'O{empty}X'   => 'OX',
  'O{undef}X'   => 'OX',
  '{x,y}'       => '1024,768',
  '{x,hello,y}' => '1024,Hello%20World%21,768',
  '?{x,empty}'  => '?1024,',
  '?{x,undef}'  => '?1024',
  '?{undef,y}'  => '?768',
  '{var:3}'     => 'val',
  '{var:30}'    => 'value',
  '{list}'      => 'red,green,blue',
  '{list*}'     => 'red,green,blue',
  '{keys}'      => 'semi,%3B,dot,.,comma,%2C',
  '{keys*}'     => 'semi=%3B,dot=.,comma=%2C',
)
?>
```

Reserved Expansion: {+var}
--------------------------

For more details see [corresponding spec](http://tools.ietf.org/html/rfc6570#section-3.2.3).