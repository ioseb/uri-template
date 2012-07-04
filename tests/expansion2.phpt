--TEST--
uri_template() expansion 2 - reserved characters test
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
  "{+var}"              => "value",
  "{+hello}"            => "Hello%20World!",
  "{+half}"             => "50%25",
  "{base}index"         => "http%3A%2F%2Fexample.com%2Fhome%2Findex",
  "{+base}index"        => "http://example.com/home/index",
  "O{+empty}X"          => "OX",
  "O{+undef}X"          => "OX",
  "{+path}/here"        => "/foo/bar/here",
  "here?ref={+path}"    => "here?ref=/foo/bar",
  "up{+path}{var}/here" => "up/foo/barvalue/here",
  "{+x,hello,y}"        => "1024,Hello%20World!,768",
  "{+path,x}/here"      => "/foo/bar,1024/here",
  "{+path:6}/here"      => "/foo/b/here",
  "{+list}"             => "red,green,blue",
  "{+list*}"            => "red,green,blue",
  "{+keys}"             => "semi,;,dot,.,comma,,",
  "{+keys*}"            => "semi=;,dot=.,comma=,"
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
array(17) {
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
    string(5) "50%25"
    ["state"]=>
    int(0)
  }
  [3]=>
  array(2) {
    ["result"]=>
    string(39) "http%3A%2F%2Fexample.com%2Fhome%2Findex"
    ["state"]=>
    int(0)
  }
  [4]=>
  array(2) {
    ["result"]=>
    string(29) "http://example.com/home/index"
    ["state"]=>
    int(0)
  }
  [5]=>
  array(2) {
    ["result"]=>
    string(2) "OX"
    ["state"]=>
    int(0)
  }
  [6]=>
  array(2) {
    ["result"]=>
    string(2) "OX"
    ["state"]=>
    int(0)
  }
  [7]=>
  array(2) {
    ["result"]=>
    string(13) "/foo/bar/here"
    ["state"]=>
    int(0)
  }
  [8]=>
  array(2) {
    ["result"]=>
    string(17) "here?ref=/foo/bar"
    ["state"]=>
    int(0)
  }
  [9]=>
  array(2) {
    ["result"]=>
    string(20) "up/foo/barvalue/here"
    ["state"]=>
    int(0)
  }
  [10]=>
  array(2) {
    ["result"]=>
    string(23) "1024,Hello%20World!,768"
    ["state"]=>
    int(0)
  }
  [11]=>
  array(2) {
    ["result"]=>
    string(18) "/foo/bar,1024/here"
    ["state"]=>
    int(0)
  }
  [12]=>
  array(2) {
    ["result"]=>
    string(11) "/foo/b/here"
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
    string(14) "red,green,blue"
    ["state"]=>
    int(0)
  }
  [15]=>
  array(2) {
    ["result"]=>
    string(20) "semi,;,dot,.,comma,,"
    ["state"]=>
    int(0)
  }
  [16]=>
  array(2) {
    ["result"]=>
    string(20) "semi=;,dot=.,comma=,"
    ["state"]=>
    int(0)
  }
}