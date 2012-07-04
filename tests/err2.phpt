--TEST--
uri_template() invalid expression - error 2 "/id*}"
--FILE--
<?php

$data = array(
  "id"          => "thing",
  "var"         => "value",
  "hello"       => "Hello World!",
  "empty"       => "",
  "path"        => "/foo/bar",
  "x"           => "1024",
  "y"           => "768",
  "list"        => array("red", "green", "blue"),
  "keys"        => array(
    "semi" => ";", 
    "dot" => ".", 
    "comma" => ","
  ),
  "example"     => "red",
  "searchTerms" => "uri templates"
);

uri_template("/id*}", $data, $result);

var_dump($result);
?>
--EXPECT--
array(3) {
  ["result"]=>
  string(6) "/id*{}"
  ["state"]=>
  int(2)
  ["expressions"]=>
  array(0) {
  }
}