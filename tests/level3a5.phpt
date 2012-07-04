--TEST--
uri_template() level 3 expansion - path style segments slash prefixed expansions
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
  "{/var}"        => "/value",
  "{/var,x}/here" => "/value/1024/here"
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
    string(6) "/value"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(16) "/value/1024/here"
    ["state"]=>
    int(0)
  }
}