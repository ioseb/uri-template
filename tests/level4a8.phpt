--TEST--
uri_template() level 4 expansion - form style query continuation expansion with value modifiers
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
  "{&var:3}" => "&var=val",
  "{&list}"  => "&list=red,green,blue",
  "{&list*}" => "&list=red&list=green&list=blue",
  "{&keys}"  => "&keys=semi,%3B,dot,.,comma,%2C",
  "{&keys*}" => "&semi=%3B&dot=.&comma=%2C"
);

$out = array();

foreach ($templates as $tpl => $expect) {
  $result = uri_template($tpl, $data);
  $out[] = $result;
}

var_dump($out);
?>
--EXPECT--
array(5) {
  [0]=>
  array(2) {
    ["result"]=>
    string(8) "&var=val"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(20) "&list=red,green,blue"
    ["state"]=>
    int(0)
  }
  [2]=>
  array(2) {
    ["result"]=>
    string(30) "&list=red&list=green&list=blue"
    ["state"]=>
    int(0)
  }
  [3]=>
  array(2) {
    ["result"]=>
    string(30) "&keys=semi,%3B,dot,.,comma,%2C"
    ["state"]=>
    int(0)
  }
  [4]=>
  array(2) {
    ["result"]=>
    string(25) "&semi=%3B&dot=.&comma=%2C"
    ["state"]=>
    int(0)
  }
}