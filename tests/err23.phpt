--TEST--
uri_template() invalid expression - error 23 "?{-join|&|var,list}"
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
  "searchTerms" => "uri templates",
  "~thing"      => "some-user",
  "query"       => "PREFIX dc: <http://purl.org/dc/elements/1.1/> SELECT ?book ?who WHERE { ?book dc:creator ?who }",
  "default-graph-uri" => array("http://www.example/book/","http://www.example/papers/"),
);

uri_template('/sparql{?query){&default-graph-uri*}', $data, $result);

var_dump($result);
?>
--EXPECT--
array(3) {
  ["result"]=>
  string(36) "/sparql{?query){&default-graph-uri*}"
  ["state"]=>
  int(2)
  ["expressions"]=>
  array(1) {
    [0]=>
    array(7) {
      ["op"]=>
      string(1) "&"
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
          string(17) "default-graph-uri"
          ["length"]=>
          int(0)
          ["explode"]=>
          bool(true)
        }
      }
    }
  }
}