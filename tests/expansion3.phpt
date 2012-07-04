--TEST--
uri_template() expansion 3 - URL fragment tests
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
  "{#var}"         => "#value",
  "{#hello}"       => "#Hello%20World!",
  "{#half}"        => "#50%25",
  "foo{#empty}"    => "foo#",
  "foo{#undef}"    => "foo",
  "{#x,hello,y}"   => "#1024,Hello%20World!,768",
  "{#path,x}/here" => "#/foo/bar,1024/here",
  "{#path:6}/here" => "#/foo/b/here",
  "{#list}"        => "#red,green,blue",
  "{#list*}"       => "#red,green,blue",
  "{#keys}"        => "#semi,;,dot,.,comma,,",
  "{#keys*}"       => "#semi=;,dot=.,comma=,"
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
array(12) {
  [0]=>
  array(2) {
    ["result"]=>
    string(6) "#value"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(15) "#Hello%20World!"
    ["state"]=>
    int(0)
  }
  [2]=>
  array(2) {
    ["result"]=>
    string(6) "#50%25"
    ["state"]=>
    int(0)
  }
  [3]=>
  array(2) {
    ["result"]=>
    string(4) "foo#"
    ["state"]=>
    int(0)
  }
  [4]=>
  array(2) {
    ["result"]=>
    string(3) "foo"
    ["state"]=>
    int(0)
  }
  [5]=>
  array(2) {
    ["result"]=>
    string(24) "#1024,Hello%20World!,768"
    ["state"]=>
    int(0)
  }
  [6]=>
  array(2) {
    ["result"]=>
    string(19) "#/foo/bar,1024/here"
    ["state"]=>
    int(0)
  }
  [7]=>
  array(2) {
    ["result"]=>
    string(12) "#/foo/b/here"
    ["state"]=>
    int(0)
  }
  [8]=>
  array(2) {
    ["result"]=>
    string(15) "#red,green,blue"
    ["state"]=>
    int(0)
  }
  [9]=>
  array(2) {
    ["result"]=>
    string(15) "#red,green,blue"
    ["state"]=>
    int(0)
  }
  [10]=>
  array(2) {
    ["result"]=>
    string(21) "#semi,;,dot,.,comma,,"
    ["state"]=>
    int(0)
  }
  [11]=>
  array(2) {
    ["result"]=>
    string(21) "#semi=;,dot=.,comma=,"
    ["state"]=>
    int(0)
  }
}