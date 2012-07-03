--TEST--
uri_template() query parameters test - example 1 two query parameters
--FILE--
<?php
$result = uri_template('http://www.example.com/foo{?query,number}', array(
  "query"  => "mycelium",
  "number" => 100
));

var_dump($result);
?>
--EXPECT--
array(2) {
  ["result"]=>
  string(52) "http://www.example.com/foo?query=mycelium&number=100"
  ["state"]=>
  int(0)
}