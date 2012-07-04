--TEST--
uri_template() query parameters test - example 2 first parameter only
--FILE--
<?php
uri_template('http://www.example.com/foo{?query,number}', array(
  "query" => "mycelium"
), $result);

unset($result['expressions']);

var_dump($result);
?>
--EXPECT--
array(2) {
  ["result"]=>
  string(41) "http://www.example.com/foo?query=mycelium"
  ["state"]=>
  int(0)
}