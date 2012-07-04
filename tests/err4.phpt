--TEST--
uri_template() invalid expression - error 4 "{var:prefix}"
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

uri_template("{var:prefix}", $data, $result);

var_dump($result);
?>
--EXPECT--
array(3) {
  ["result"]=>
  string(12) "{var:prefix}"
  ["state"]=>
  int(3)
  ["expressions"]=>
  array(1) {
    [0]=>
    array(7) {
      ["op"]=>
      string(0) ""
      ["sep"]=>
      string(1) ","
      ["ifemp"]=>
      string(0) ""
      ["allow"]=>
      bool(false)
      ["named"]=>
      bool(false)
      ["error"]=>
      bool(true)
      ["vars"]=>
      array(1) {
        [0]=>
        array(3) {
          ["name"]=>
          string(10) "var:prefix"
          ["length"]=>
          int(0)
          ["explode"]=>
          bool(false)
        }
      }
    }
  }
}