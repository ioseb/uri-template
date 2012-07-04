--TEST--
uri_template() level 3 expansion - string expansions with multiple variables
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
  "map?{x,y}"   => "map?1024,768",
  "{x,hello,y}" => "1024,Hello%20World%21,768"
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
    string(12) "map?1024,768"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(25) "1024,Hello%20World%21,768"
    ["state"]=>
    int(0)
  }
}