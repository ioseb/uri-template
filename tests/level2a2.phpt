--TEST--
uri_template() level 2 expansion - fragment expansion
--FILE--
<?php

$data = array(
  "var"   => "value",
  "hello" => "Hello World!",
  "path"  => "/foo/bar"
);

$templates = array(
  "X{#var}"   => "X#value",
  "X{#hello}" => "X#Hello%20World!"
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
array(2) {
  [0]=>
  array(2) {
    ["result"]=>
    string(7) "X#value"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(16) "X#Hello%20World!"
    ["state"]=>
    int(0)
  }
}