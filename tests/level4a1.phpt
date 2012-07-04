--TEST--
uri_template() level 4 expansion - string expansion with value modifiers
--FILE--
<?php

$data = array(
  "var"   => "value",
  "hello" => "Hello World!",
  "path"  => "/foo/bar",
  "list"  => array("red", "green", "blue"),
  "keys"  => array(
    "semi"  => ";",
    "dot"   => ".",
    "comma" => ","
  )
);

$templates = array(
  "{var:3}"  => "val",
  "{var:30}" => "value",
  "{list}"   => "red,green,blue",
  "{list*}"  => "red,green,blue",
  "{keys}"   => "semi,%3B,dot,.,comma,%2C",
  "{keys*}"  => "semi=%3B,dot=.,comma=%2C"
);

$out = array();

foreach ($templates as $tpl => $expect) {
  $result = NULL;
  uri_template($tpl, $data, $result);
  unset($result['expressions']);
  $out[] = $result;
}

var_dump($out);
?>
--EXPECT--
array(6) {
  [0]=>
  array(2) {
    ["result"]=>
    string(3) "val"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(5) "value"
    ["state"]=>
    int(0)
  }
  [2]=>
  array(2) {
    ["result"]=>
    string(14) "red,green,blue"
    ["state"]=>
    int(0)
  }
  [3]=>
  array(2) {
    ["result"]=>
    string(14) "red,green,blue"
    ["state"]=>
    int(0)
  }
  [4]=>
  array(2) {
    ["result"]=>
    string(24) "semi,%3B,dot,.,comma,%2C"
    ["state"]=>
    int(0)
  }
  [5]=>
  array(2) {
    ["result"]=>
    string(24) "semi=%3B,dot=.,comma=%2C"
    ["state"]=>
    int(0)
  }
}