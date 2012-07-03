--TEST--
uri_template() query parameters test - example 3 second parameter only
--FILE--
<?php
$result = uri_template('http://www.example.com/foo{?query,number}', array(
  "number" => 100,
));

var_dump($result);
?>
--EXPECT--
array(2) {
  ["result"]=>
  string(37) "http://www.example.com/foo?number=100"
  ["state"]=>
  int(0)
}