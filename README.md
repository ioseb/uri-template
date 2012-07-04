URI Template PHP Extension
============

PHP extension implementation of RFC-6570 in C - http://tools.ietf.org/html/rfc6570

Usage examples (see unit tests for more examples)
--------------------------------------------------

```php
<?php
$data = array(
	"query"  => "mycelium",
	"number" => 100
);

echo uri_template('http://www.example.com/foo{?query,number}', $data);

// http://www.example.com/foo?query=mycelium&number=100
```