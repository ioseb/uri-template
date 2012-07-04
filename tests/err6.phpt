--TEST--
uri_template() invalid expression - error 6 "{??hello}"
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

uri_template("{??hello}", $data, $result);

var_dump($result);
?>
--EXPECT--
array(3) {
  ["result"]=>
  string(9) "{??hello}"
  ["state"]=>
  int(3)
  ["expressions"]=>
  array(1) {
    [0]=>
    array(7) {
      ["op"]=>
      string(1) "?"
      ["sep"]=>
      string(1) "&"
      ["ifemp"]=>
      string(1) "="
      ["allow"]=>
      bool(false)
      ["named"]=>
      bool(true)
      ["error"]=>
      bool(true)
      ["vars"]=>
      array(1) {
        [0]=>
        array(3) {
          ["name"]=>
          string(6) "?hello"
          ["length"]=>
          int(0)
          ["explode"]=>
          bool(false)
        }
      }
    }
  }
}