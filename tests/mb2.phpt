--TEST--
uri_template() multibyte string - string test damaged string "'台北Táiběi"
--FILE--
<?php

$s = '台北Táiběi';
$s[0] = 'a';
$s[2] = 'b';
$s[4] = 'c';

$data = array(
  'var' => $s
);

$templates = array(
  '{var}',
  '{var:1}',
  '{var:2}',
  '{var:3}',
  '{var:4}',
);

$out = array();

foreach ($templates as $tpl) {
  uri_template($tpl, $data, $result);
  unset($result['expressions']);
  $out[] = $result;
}

var_dump($out);
?>
--EXPECT--
array(5) {
  [0]=>
  array(2) {
    ["result"]=>
    string(46) "a%EF%BF%BDb%EF%BF%BDc%EF%BF%BDT%C3%A1ib%C4%9Bi"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(1) "a"
    ["state"]=>
    int(0)
  }
  [2]=>
  array(2) {
    ["result"]=>
    string(10) "a%EF%BF%BD"
    ["state"]=>
    int(0)
  }
  [3]=>
  array(2) {
    ["result"]=>
    string(11) "a%EF%BF%BDb"
    ["state"]=>
    int(0)
  }
  [4]=>
  array(2) {
    ["result"]=>
    string(20) "a%EF%BF%BDb%EF%BF%BD"
    ["state"]=>
    int(0)
  }
}