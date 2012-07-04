--TEST--
uri_template() expansion 6 - path style tests
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
  "{;who}"         => ";who=fred",
  "{;half}"        => ";half=50%25",
  "{;empty}"       => ";empty",
  "{;v,empty,who}" => ";v=6;empty;who=fred",
  "{;v,bar,who}"   => ";v=6;who=fred",
  "{;x,y}"         => ";x=1024;y=768",
  "{;x,y,empty}"   => ";x=1024;y=768;empty",
  "{;x,y,undef}"   => ";x=1024;y=768",
  "{;hello:5}"     => ";hello=Hello",
  "{;list}"        => ";list=red,green,blue",
  "{;list*}"       => ";list=red;list=green;list=blue",
  "{;keys}"        => ";keys=semi,%3B,dot,.,comma,%2C",
  "{;keys*}"       => ";semi=%3B;dot=.;comma=%2C"
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
array(13) {
  [0]=>
  array(2) {
    ["result"]=>
    string(9) ";who=fred"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(11) ";half=50%25"
    ["state"]=>
    int(0)
  }
  [2]=>
  array(2) {
    ["result"]=>
    string(6) ";empty"
    ["state"]=>
    int(0)
  }
  [3]=>
  array(2) {
    ["result"]=>
    string(19) ";v=6;empty;who=fred"
    ["state"]=>
    int(0)
  }
  [4]=>
  array(2) {
    ["result"]=>
    string(13) ";v=6;who=fred"
    ["state"]=>
    int(0)
  }
  [5]=>
  array(2) {
    ["result"]=>
    string(13) ";x=1024;y=768"
    ["state"]=>
    int(0)
  }
  [6]=>
  array(2) {
    ["result"]=>
    string(19) ";x=1024;y=768;empty"
    ["state"]=>
    int(0)
  }
  [7]=>
  array(2) {
    ["result"]=>
    string(13) ";x=1024;y=768"
    ["state"]=>
    int(0)
  }
  [8]=>
  array(2) {
    ["result"]=>
    string(12) ";hello=Hello"
    ["state"]=>
    int(0)
  }
  [9]=>
  array(2) {
    ["result"]=>
    string(20) ";list=red,green,blue"
    ["state"]=>
    int(0)
  }
  [10]=>
  array(2) {
    ["result"]=>
    string(30) ";list=red;list=green;list=blue"
    ["state"]=>
    int(0)
  }
  [11]=>
  array(2) {
    ["result"]=>
    string(30) ";keys=semi,%3B,dot,.,comma,%2C"
    ["state"]=>
    int(0)
  }
  [12]=>
  array(2) {
    ["result"]=>
    string(25) ";semi=%3B;dot=.;comma=%2C"
    ["state"]=>
    int(0)
  }
}