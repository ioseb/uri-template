--TEST--
uri_template() expansion 0 - count variable test
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
  '{count}'   => 'one,two,three',
  '{count*}'  => 'one,two,three',
  '{/count}'  => '/one,two,three',
  '{/count*}' => '/one/two/three',
  '{;count}'  => ';count=one,two,three',
  '{;count*}' => ';count=one;count=two;count=three',
  '{?count}'  => '?count=one,two,three',
  '{?count*}' => '?count=one&count=two&count=three',
  '{&count*}' => '&count=one&count=two&count=three',
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
array(9) {
  [0]=>
  array(2) {
    ["result"]=>
    string(13) "one,two,three"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(13) "one,two,three"
    ["state"]=>
    int(0)
  }
  [2]=>
  array(2) {
    ["result"]=>
    string(14) "/one,two,three"
    ["state"]=>
    int(0)
  }
  [3]=>
  array(2) {
    ["result"]=>
    string(14) "/one/two/three"
    ["state"]=>
    int(0)
  }
  [4]=>
  array(2) {
    ["result"]=>
    string(20) ";count=one,two,three"
    ["state"]=>
    int(0)
  }
  [5]=>
  array(2) {
    ["result"]=>
    string(32) ";count=one;count=two;count=three"
    ["state"]=>
    int(0)
  }
  [6]=>
  array(2) {
    ["result"]=>
    string(20) "?count=one,two,three"
    ["state"]=>
    int(0)
  }
  [7]=>
  array(2) {
    ["result"]=>
    string(32) "?count=one&count=two&count=three"
    ["state"]=>
    int(0)
  }
  [8]=>
  array(2) {
    ["result"]=>
    string(32) "&count=one&count=two&count=three"
    ["state"]=>
    int(0)
  }
}