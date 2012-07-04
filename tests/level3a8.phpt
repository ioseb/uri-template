--TEST--
uri_template() level 3 expansion - form style query continuation
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
  "?fixed=yes{&x}" => "?fixed=yes&x=1024",
  "{&x,y,empty}"   => "&x=1024&y=768&empty="
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
    string(17) "?fixed=yes&x=1024"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(20) "&x=1024&y=768&empty="
    ["state"]=>
    int(0)
  }
}