--TEST--
uri_template() expansion 5 - path segments tests
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
  "{/who}"          => "/fred",
  "{/who,who}"      => "/fred/fred",
  "{/half,who}"     => "/50%25/fred",
  "{/who,dub}"      => "/fred/me%2Ftoo",
  "{/var}"          => "/value",
  "{/var,empty}"    => "/value/",
  "{/var,undef}"    => "/value",
  "{/var,x}/here"   => "/value/1024/here",
  "{/var:1,var}"    => "/v/value",
  "{/list}"         => "/red,green,blue",
  "{/list*}"        => "/red/green/blue",
  "{/list*,path:4}" => "/red/green/blue/%2Ffoo",
  "{/keys}"         => "/semi,%3B,dot,.,comma,%2C",
  "{/keys*}"        => "/semi=%3B/dot=./comma=%2C"
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
    string(5) "/fred"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(10) "/fred/fred"
    ["state"]=>
    int(0)
  }
  [2]=>
  array(2) {
    ["result"]=>
    string(11) "/50%25/fred"
    ["state"]=>
    int(0)
  }
  [3]=>
  array(2) {
    ["result"]=>
    string(14) "/fred/me%2Ftoo"
    ["state"]=>
    int(0)
  }
  [4]=>
  array(2) {
    ["result"]=>
    string(6) "/value"
    ["state"]=>
    int(0)
  }
  [5]=>
  array(2) {
    ["result"]=>
    string(7) "/value/"
    ["state"]=>
    int(0)
  }
  [6]=>
  array(2) {
    ["result"]=>
    string(6) "/value"
    ["state"]=>
    int(0)
  }
  [7]=>
  array(2) {
    ["result"]=>
    string(16) "/value/1024/here"
    ["state"]=>
    int(0)
  }
  [8]=>
  array(2) {
    ["result"]=>
    string(8) "/v/value"
    ["state"]=>
    int(0)
  }
  [9]=>
  array(2) {
    ["result"]=>
    string(15) "/red,green,blue"
    ["state"]=>
    int(0)
  }
  [10]=>
  array(2) {
    ["result"]=>
    string(15) "/red/green/blue"
    ["state"]=>
    int(0)
  }
  [11]=>
  array(2) {
    ["result"]=>
    string(22) "/red/green/blue/%2Ffoo"
    ["state"]=>
    int(0)
  }
  [12]=>
  array(2) {
    ["result"]=>
    string(25) "/semi,%3B,dot,.,comma,%2C"
    ["state"]=>
    int(0)
  }
  [13]=>
  array(2) {
    ["result"]=>
    string(25) "/semi=%3B/dot=./comma=%2C"
    ["state"]=>
    int(0)
  }
}