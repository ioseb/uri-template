--TEST--
uri_template() level 3 expansion - dot prefixed label expansions
--FILE--
<?php

$data = array(
  "var"   => "value",
  "hello" => "Hello World!",
  "empty" => "",
  "path"  => "/foo/bar",
  "x"     => "1024",
  "y"     => "768"
);

$templates = array(
  "X{.var}" => "X.value",
  "X{.x,y}" => "X.1024.768"
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
    string(7) "X.value"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(10) "X.1024.768"
    ["state"]=>
    int(0)
  }
}