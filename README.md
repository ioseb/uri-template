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

Capturing Execution State
-------------------------

uri_template(...) function supports third optional parameter for capturing URI template parsing and processing state. Sometimes it's handy to see how template was parsed. Example below demonstrates usage of this parameter:

```php
<?php
$data = array(
  "id"      => array("person","albums"),
  "token"   => "12345",
  "fields"  => array("id", "name", "picture"),
);

$tpl = "{/id*}{?fields,token}";
uri_template($tpl, $data, $result);
?>
```

After executing example code above $result variable will have following structure:

```php
<?php
array (
  'result' => '/person/albums?fields=id,name,picture&token=12345',
  'state' => 0,
  'expressions' => array (
    0 => array (
      'op' => '/',
      'sep' => '/',
      'ifemp' => '',
      'allow' => false,
      'named' => false,
      'error' => false,
      'vars' => array (
        0 => array (
          'name' => 'id',
          'length' => 0,
          'explode' => true,
        ),
      ),
    ),
    1 => array (
      'op' => '?',
      'sep' => '&',
      'ifemp' => '=',
      'allow' => false,
      'named' => true,
      'error' => false,
      'vars' => array (
        0 => array (
          'name' => 'fields',
          'length' => 0,
          'explode' => false,
        ),
        1 => array (
          'name' => 'token',
          'length' => 0,
          'explode' => false,
        ),
      ),
    ),
  ),
)
?>
```

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

```php
<?php
$templates = array(
  "{+var}",
  "{+hello}",
  "{+half}",
  "{base}index",
  "{+base}index",
  "O{+empty}X",
  "O{+undef}X",
  "{+path}/here",
  "here?ref={+path}",
  "up{+path}{var}/here",
  "{+x,hello,y}",
  "{+path,x}/here",
  "{+path:6}/here",
  "{+list}",
  "{+list*}",
  "{+keys}",
  "{+keys*}"
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
  '{+var}'              => 'value',
  '{+hello}'            => 'Hello%20World!',
  '{+half}'             => '50%25',
  '{base}index'         => 'http%3A%2F%2Fexample.com%2Fhome%2Findex',
  '{+base}index'        => 'http://example.com/home/index',
  'O{+empty}X'          => 'OX',
  'O{+undef}X'          => 'OX',
  '{+path}/here'        => '/foo/bar/here',
  'here?ref={+path}'    => 'here?ref=/foo/bar',
  'up{+path}{var}/here' => 'up/foo/barvalue/here',
  '{+x,hello,y}'        => '1024,Hello%20World!,768',
  '{+path,x}/here'      => '/foo/bar,1024/here',
  '{+path:6}/here'      => '/foo/b/here',
  '{+list}'             => 'red,green,blue',
  '{+list*}'            => 'red,green,blue',
  '{+keys}'             => 'semi,;,dot,.,comma,,',
  '{+keys*}'            => 'semi=;,dot=.,comma=,',
)
?>
```

Fragment Expansion: {#var}
--------------------------

For more details see [corresponding spec](http://tools.ietf.org/html/rfc6570#section-3.2.4).

```php
<?php
$templates = array(
  "{#var}",
  "{#hello}",
  "{#half}",
  "foo{#empty}",
  "foo{#undef}",
  "{#x,hello,y}",
  "{#path,x}/here",
  "{#path:6}/here",
  "{#list}",
  "{#list*}",
  "{#keys}",
  "{#keys*}"
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
array(
  "{#var}"         => "#value",
  "{#hello}"       => "#Hello%20World!",
  "{#half}"        => "#50%25",
  "foo{#empty}"    => "foo#",
  "foo{#undef}"    => "foo",
  "{#x,hello,y}"   => "#1024,Hello%20World!,768",
  "{#path,x}/here" => "#/foo/bar,1024/here",
  "{#path:6}/here" => "#/foo/b/here",
  "{#list}"        => "#red,green,blue",
  "{#list*}"       => "#red,green,blue",
  "{#keys}"        => "#semi,;,dot,.,comma,,",
  "{#keys*}"       => "#semi=;,dot=.,comma=,"
)
?>
```

Label Expansion with Dot-Prefix: {.var}
---------------------------------------

For more details see [corresponding spec](http://tools.ietf.org/html/rfc6570#section-3.2.5).

```php
<?php
$templates = array(
  "{.who}",
  "{.who,who}",
  "{.half,who}",
  "www{.dom*}",
  "X{.var}",
  "X{.empty}",
  "X{.undef}",
  "X{.var:3}",
  "X{.list}",
  "X{.list*}",
  "X{.keys}",
  "X{.keys*}",
  "X{.empty_keys}",
  "X{.empty_keys*}"
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
array(
  "{.who}"          => ".fred",
  "{.who,who}"      => ".fred.fred",
  "{.half,who}"     => ".50%25.fred",
  "www{.dom*}"      => "www.example.com",
  "X{.var}"         => "X.value",
  "X{.empty}"       => "X.",
  "X{.undef}"       => "X",
  "X{.var:3}"       => "X.val",
  "X{.list}"        => "X.red,green,blue",
  "X{.list*}"       => "X.red.green.blue",
  "X{.keys}"        => "X.semi,%3B,dot,.,comma,%2C",
  "X{.keys*}"       => "X.semi=%3B.dot=..comma=%2C",
  "X{.empty_keys}"  => "X",
  "X{.empty_keys*}" => "X"
)
?>
```

Path Segment Expansion: {/var}
------------------------------

For more details see [corresponding spec](http://tools.ietf.org/html/rfc6570#section-3.2.6).

```php
<?php
$templates = array(
  "{/who}",
  "{/who,who}",
  "{/half,who}",
  "{/who,dub}",
  "{/var}",
  "{/var,empty}",
  "{/var,undef}",
  "{/var,x}/here",
  "{/var:1,var}",
  "{/list}",
  "{/list*}",
  "{/list*,path:4}",
  "{/keys}",
  "{/keys*}"
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
array(
  "{/who}"          => "/fred",
  "{/who,who}"      => "/fred/fred",
  "{/half,who}"     => "/50%25/fred",
  "{/who,dub}"      => "/fred/me%2Ftoo",
  "{/var}"          => "/value",
  "{/var,empty}"    => "/value/",
  "{/var,undef}"    => "/value",
  "{/var,x}/here"   => "/value/1024/here",
  "{/var:1,var}"    => "/v/value",
  "{/list}"         => "/red,green,blue",
  "{/list*}"        => "/red/green/blue",
  "{/list*,path:4}" => "/red/green/blue/%2Ffoo",
  "{/keys}"         => "/semi,%3B,dot,.,comma,%2C",
  "{/keys*}"        => "/semi=%3B/dot=./comma=%2C"
)
?>
```

Path-Style Parameter Expansion: {;var}
--------------------------------------

For more details see [corresponding spec](http://tools.ietf.org/html/rfc6570#section-3.2.7).

```php
<?php
$templates = array(
  "{;who}",
  "{;half}",
  "{;empty}",
  "{;v,empty,who}",
  "{;v,bar,who}",
  "{;x,y}",
  "{;x,y,empty}",
  "{;x,y,undef}",
  "{;hello:5}",
  "{;list}",
  "{;list*}",
  "{;keys}",
  "{;keys*}"
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
array(
  "{;who}"         => ";who=fred",
  "{;half}"        => ";half=50%25",
  "{;empty}"       => ";empty",
  "{;v,empty,who}" => ";v=6;empty;who=fred",
  "{;v,bar,who}"   => ";v=6;who=fred",
  "{;x,y}"         => ";x=1024;y=768",
  "{;x,y,empty}"   => ";x=1024;y=768;empty",
  "{;x,y,undef}"   => ";x=1024;y=768",
  "{;hello:5}"     => ";hello=Hello",
  "{;list}"        => ";list=red,green,blue",
  "{;list*}"       => ";list=red;list=green;list=blue",
  "{;keys}"        => ";keys=semi,%3B,dot,.,comma,%2C",
  "{;keys*}"       => ";semi=%3B;dot=.;comma=%2C"
)
?>
```

Form-Style Query Expansion: {?var}
----------------------------------

For more details see [corresponding spec](http://tools.ietf.org/html/rfc6570#section-3.2.8).

```php
<?php
$templates = array(
  "{?who}",
  "{?half}",
  "{?x,y}",
  "{?x,y,empty}",
  "{?x,y,undef}",
  "{?var:3}",
  "{?list}",
  "{?list*}",
  "{?keys}",
  "{?keys*}"
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
array(
  "{?who}"       => "?who=fred",
  "{?half}"      => "?half=50%25",
  "{?x,y}"       => "?x=1024&y=768",
  "{?x,y,empty}" => "?x=1024&y=768&empty=",
  "{?x,y,undef}" => "?x=1024&y=768",
  "{?var:3}"     => "?var=val",
  "{?list}"      => "?list=red,green,blue",
  "{?list*}"     => "?list=red&list=green&list=blue",
  "{?keys}"      => "?keys=semi,%3B,dot,.,comma,%2C",
  "{?keys*}"     => "?semi=%3B&dot=.&comma=%2C"
)
?>
```

Form-Style Query Continuation: {&var}
-------------------------------------

For more details see [corresponding spec](http://tools.ietf.org/html/rfc6570#section-3.2.9).

```php
<?php
$templates = array(
  "{&who}",
  "{&half}",
  "?fixed=yes{&x}",
  "{&x,y,empty}",
  "{&x,y,undef}",
  "{&var:3}",
  "{&list}",
  "{&list*}",
  "{&keys}",
  "{&keys*}"
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
array(
  "{&who}"         => "&who=fred",
  "{&half}"        => "&half=50%25",
  "?fixed=yes{&x}" => "?fixed=yes&x=1024",
  "{&x,y,empty}"   => "&x=1024&y=768&empty=",
  "{&x,y,undef}"   => "&x=1024&y=768",
  "{&var:3}"       => "&var=val",
  "{&list}"        => "&list=red,green,blue",
  "{&list*}"       => "&list=red&list=green&list=blue",
  "{&keys}"        => "&keys=semi,%3B,dot,.,comma,%2C",
  "{&keys*}"       => "&semi=%3B&dot=.&comma=%2C"
)
?>
```