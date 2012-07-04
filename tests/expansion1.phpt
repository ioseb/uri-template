--TEST--
uri_template() expansion 1 - basic tests
--FILE--
<?php
$data = array(
  'count'      => array("one", "two", "three"),
  'dom'        => array("example", "com"),
  'dub'        => "me/too",
  'hello'      => "Hello World!",
  'half'       => "50%",
  'var'        => "value",
  'who'        => "fred",
  'base'       => "http://example.com/home/",
  'path'       => "/foo/bar",
  'list'       => array("red", "green", "blue"),
  'keys'       => array(
    "semi"  => ";",
    "dot"   => ".",
    "comma" => ",",
  ),
  'v'          => "6",
  'x'          => "1024",
  'y'          => "768",
  'empty'      => "",
  'empty_keys' => array(),
  'undef'      => null,
);

$templates = array(
  "{var}"       => "value",
  "{hello}"     => "Hello%20World%21",
  "{half}"      => "50%25",
  "O{empty}X"   => "OX",
  "O{undef}X"   => "OX",
  "{x,y}"       => "1024,768",
  "{x,hello,y}" => "1024,Hello%20World%21,768",
  "?{x,empty}"  => "?1024,",
  "?{x,undef}"  => "?1024",
  "?{undef,y}"  => "?768",
  "{var:3}"     => "val",
  "{var:30}"    => "value",
  "{list}"      => "red,green,blue",
  "{list*}"     => "red,green,blue",
  "{keys}"      => "semi,%3B,dot,.,comma,%2C",
  "{keys*}"     => "semi=%3B,dot=.,comma=%2C"
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
array(16) {
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
  [2]=>
  array(2) {
    ["result"]=>
    string(5) "50%25"
    ["state"]=>
    int(0)
  }
  [3]=>
  array(2) {
    ["result"]=>
    string(2) "OX"
    ["state"]=>
    int(0)
  }
  [4]=>
  array(2) {
    ["result"]=>
    string(2) "OX"
    ["state"]=>
    int(0)
  }
  [5]=>
  array(2) {
    ["result"]=>
    string(8) "1024,768"
    ["state"]=>
    int(0)
  }
  [6]=>
  array(2) {
    ["result"]=>
    string(25) "1024,Hello%20World%21,768"
    ["state"]=>
    int(0)
  }
  [7]=>
  array(2) {
    ["result"]=>
    string(6) "?1024,"
    ["state"]=>
    int(0)
  }
  [8]=>
  array(2) {
    ["result"]=>
    string(5) "?1024"
    ["state"]=>
    int(0)
  }
  [9]=>
  array(2) {
    ["result"]=>
    string(4) "?768"
    ["state"]=>
    int(0)
  }
  [10]=>
  array(2) {
    ["result"]=>
    string(3) "val"
    ["state"]=>
    int(0)
  }
  [11]=>
  array(2) {
    ["result"]=>
    string(5) "value"
    ["state"]=>
    int(0)
  }
  [12]=>
  array(2) {
    ["result"]=>
    string(14) "red,green,blue"
    ["state"]=>
    int(0)
  }
  [13]=>
  array(2) {
    ["result"]=>
    string(14) "red,green,blue"
    ["state"]=>
    int(0)
  }
  [14]=>
  array(2) {
    ["result"]=>
    string(24) "semi,%3B,dot,.,comma,%2C"
    ["state"]=>
    int(0)
  }
  [15]=>
  array(2) {
    ["result"]=>
    string(24) "semi=%3B,dot=.,comma=%2C"
    ["state"]=>
    int(0)
  }
}