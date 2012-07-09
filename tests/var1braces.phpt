--TEST--
uri_template() invalid expression - braces test "{", "}", "{}"
--FILE--
<?php

$out = array();

foreach (array('{', '}', '{}') as $tpl) {
  $result = NULL;
  uri_template($tpl, array(), $result);
  
  $out[] = $result;
}

var_dump($out);
?>
--EXPECT--
array(3) {
  [0]=>
  array(3) {
    ["result"]=>
    string(1) "{"
    ["state"]=>
    int(2)
    ["expressions"]=>
    array(0) {
    }
  }
  [1]=>
  array(3) {
    ["result"]=>
    string(1) "}"
    ["state"]=>
    int(2)
    ["expressions"]=>
    array(0) {
    }
  }
  [2]=>
  array(3) {
    ["result"]=>
    string(2) "{}"
    ["state"]=>
    int(0)
    ["expressions"]=>
    array(0) {
    }
  }
}