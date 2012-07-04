--TEST--
uri_template() expansion 4 - label tests
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
  "{.who}"          => ".fred",
  "{.who,who}"      => ".fred.fred",
  "{.half,who}"     => ".50%25.fred",
  "www{.dom*}"      => "www.example.com",
  "X{.var}"         => "X.value",
  "X{.empty}"       => "X.",
  "X{.undef}"       => "X",
  "X{.var:3}"       => "X.val",
  "X{.list}"        => "X.red,green,blue",
  "X{.list*}"       => "X.red.green.blue",
  "X{.keys}"        => "X.semi,%3B,dot,.,comma,%2C",
  "X{.keys*}"       => "X.semi=%3B.dot=..comma=%2C",
  "X{.empty_keys}"  => "X",
  "X{.empty_keys*}" => "X"
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
array(14) {
  [0]=>
  array(2) {
    ["result"]=>
    string(5) ".fred"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(10) ".fred.fred"
    ["state"]=>
    int(0)
  }
  [2]=>
  array(2) {
    ["result"]=>
    string(11) ".50%25.fred"
    ["state"]=>
    int(0)
  }
  [3]=>
  array(2) {
    ["result"]=>
    string(15) "www.example.com"
    ["state"]=>
    int(0)
  }
  [4]=>
  array(2) {
    ["result"]=>
    string(7) "X.value"
    ["state"]=>
    int(0)
  }
  [5]=>
  array(2) {
    ["result"]=>
    string(2) "X."
    ["state"]=>
    int(0)
  }
  [6]=>
  array(2) {
    ["result"]=>
    string(1) "X"
    ["state"]=>
    int(0)
  }
  [7]=>
  array(2) {
    ["result"]=>
    string(5) "X.val"
    ["state"]=>
    int(0)
  }
  [8]=>
  array(2) {
    ["result"]=>
    string(16) "X.red,green,blue"
    ["state"]=>
    int(0)
  }
  [9]=>
  array(2) {
    ["result"]=>
    string(16) "X.red.green.blue"
    ["state"]=>
    int(0)
  }
  [10]=>
  array(2) {
    ["result"]=>
    string(26) "X.semi,%3B,dot,.,comma,%2C"
    ["state"]=>
    int(0)
  }
  [11]=>
  array(2) {
    ["result"]=>
    string(26) "X.semi=%3B.dot=..comma=%2C"
    ["state"]=>
    int(0)
  }
  [12]=>
  array(2) {
    ["result"]=>
    string(1) "X"
    ["state"]=>
    int(0)
  }
  [13]=>
  array(2) {
    ["result"]=>
    string(1) "X"
    ["state"]=>
    int(0)
  }
}