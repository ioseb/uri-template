--TEST--
uri_template() query parameters test - example 4 no parameters
--FILE--
<?php
uri_template('http://www.example.com/foo{?query,number}', array(), $result);
unset($result['expressions']);

var_dump($result);
?>
--EXPECT--
array(2) {
  ["result"]=>
  string(26) "http://www.example.com/foo"
  ["state"]=>
  int(0)
}