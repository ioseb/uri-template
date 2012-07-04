--TEST--
uri_template() level 3 expansion - path style parameters semi colon prefixed expansions
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
  "{;x,y}"       => ";x=1024;y=768",
  "{;x,y,empty}" => ";x=1024;y=768;empty"
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
    string(13) ";x=1024;y=768"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(19) ";x=1024;y=768;empty"
    ["state"]=>
    int(0)
  }
}