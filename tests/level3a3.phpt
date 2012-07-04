--TEST--
uri_template() level 3 expansion - fragment expansions with multiple variables
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
  "{#x,hello,y}"   => "#1024,Hello%20World!,768",
  "{#path,x}/here" => "#/foo/bar,1024/here"
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
    string(24) "#1024,Hello%20World!,768"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(19) "#/foo/bar,1024/here"
    ["state"]=>
    int(0)
  }
}