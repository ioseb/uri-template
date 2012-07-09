--TEST--
uri_template() invalid expression - empty template
--FILE--
<?php
uri_template('', array(), $result);

var_dump($result);
?>
--EXPECT--
array(3) {
  ["result"]=>
  string(0) ""
  ["state"]=>
  int(0)
  ["expressions"]=>
  array(0) {
  }
}