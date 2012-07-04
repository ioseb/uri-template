--TEST--
uri_template() level 2 expansion - reserved characters expansion
--FILE--
<?php

$data = array(
  "var"   => "value",
  "hello" => "Hello World!",
  "path"  => "/foo/bar"
);

$templates = array(
  "{+var}"           => "value",
  "{+hello}"         => "Hello%20World!",
  "{+path}/here"     => "/foo/bar/here",
  "here?ref={+path}" => "here?ref=/foo/bar"
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
array(4) {
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
    string(14) "Hello%20World!"
    ["state"]=>
    int(0)
  }
  [2]=>
  array(2) {
    ["result"]=>
    string(13) "/foo/bar/here"
    ["state"]=>
    int(0)
  }
  [3]=>
  array(2) {
    ["result"]=>
    string(17) "here?ref=/foo/bar"
    ["state"]=>
    int(0)
  }
}