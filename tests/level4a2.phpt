--TEST--
uri_template() level 4 expansion - plus operator and reserved chars with value modifiers
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
  "{+path:6}/here" => "/foo/b/here",
  "{+list}"        => "red,green,blue",
  "{+list*}"       => "red,green,blue",
  "{+keys}"        => "semi,;,dot,.,comma,,",
  "{+keys*}"       => "semi=;,dot=.,comma=,"
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
    string(11) "/foo/b/here"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(14) "red,green,blue"
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
    string(20) "semi,;,dot,.,comma,,"
    ["state"]=>
    int(0)
  }
  [4]=>
  array(2) {
    ["result"]=>
    string(20) "semi=;,dot=.,comma=,"
    ["state"]=>
    int(0)
  }
}