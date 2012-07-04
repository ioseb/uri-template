--TEST--
uri_template() level 4 expansion - dot prefixed label expansion with value modifiers
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
  "X{.var:3}" => "X.val",
  "X{.list}"  => "X.red,green,blue",
  "X{.list*}" => "X.red.green.blue",
  "X{.keys}"  => "X.semi,%3B,dot,.,comma,%2C",
  "X{.keys*}" => "X.semi=%3B.dot=..comma=%2C"
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
array(5) {
  [0]=>
  array(2) {
    ["result"]=>
    string(5) "X.val"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(16) "X.red,green,blue"
    ["state"]=>
    int(0)
  }
  [2]=>
  array(2) {
    ["result"]=>
    string(16) "X.red.green.blue"
    ["state"]=>
    int(0)
  }
  [3]=>
  array(2) {
    ["result"]=>
    string(26) "X.semi,%3B,dot,.,comma,%2C"
    ["state"]=>
    int(0)
  }
  [4]=>
  array(2) {
    ["result"]=>
    string(26) "X.semi=%3B.dot=..comma=%2C"
    ["state"]=>
    int(0)
  }
}