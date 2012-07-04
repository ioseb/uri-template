--TEST--
uri_template() invalid expression - error 12 "{?empty=default,var}"
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

uri_template('{?empty=default,var}', $data, $result);

var_dump($result);
?>
--EXPECT--
array(3) {
  ["result"]=>
  string(20) "{?empty=default,var}"
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
      array(2) {
        [0]=>
        array(3) {
          ["name"]=>
          string(13) "empty=default"
          ["length"]=>
          int(0)
          ["explode"]=>
          bool(false)
        }
        [1]=>
        array(3) {
          ["name"]=>
          string(3) "var"
          ["length"]=>
          int(0)
          ["explode"]=>
          bool(false)
        }
      }
    }
  }
}