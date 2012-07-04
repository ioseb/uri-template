--TEST--
uri_template() level 1 expansion - simple expansion
--FILE--
<?php

$data = array(
  "var"   => "value",
  "hello" => "Hello World!"
);

$templates = array(
  "{var}"   => "value",
  "{hello}" => "Hello%20World%21"
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
    string(5) "value"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(16) "Hello%20World%21"
    ["state"]=>
    int(0)
  }
}