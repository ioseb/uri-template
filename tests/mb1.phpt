--TEST--
uri_template() multibyte string - test 1 "'台北Táiběi"
--FILE--
<?php

$data = array(
  'var' => '台北Táiběi'
);

$templates = array(
  '{var}',
  '{var:1}',
  '{var:2}',
  '{var:3}',
  '{var:4}',
  '{var:5}',
  '{var:6}',
  '{var:8}',
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
array(8) {
  [0]=>
  array(2) {
    ["result"]=>
    string(34) "%E5%8F%B0%E5%8C%97T%C3%A1ib%C4%9Bi"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(9) "%E5%8F%B0"
    ["state"]=>
    int(0)
  }
  [2]=>
  array(2) {
    ["result"]=>
    string(18) "%E5%8F%B0%E5%8C%97"
    ["state"]=>
    int(0)
  }
  [3]=>
  array(2) {
    ["result"]=>
    string(19) "%E5%8F%B0%E5%8C%97T"
    ["state"]=>
    int(0)
  }
  [4]=>
  array(2) {
    ["result"]=>
    string(25) "%E5%8F%B0%E5%8C%97T%C3%A1"
    ["state"]=>
    int(0)
  }
  [5]=>
  array(2) {
    ["result"]=>
    string(26) "%E5%8F%B0%E5%8C%97T%C3%A1i"
    ["state"]=>
    int(0)
  }
  [6]=>
  array(2) {
    ["result"]=>
    string(27) "%E5%8F%B0%E5%8C%97T%C3%A1ib"
    ["state"]=>
    int(0)
  }
  [7]=>
  array(2) {
    ["result"]=>
    string(34) "%E5%8F%B0%E5%8C%97T%C3%A1ib%C4%9Bi"
    ["state"]=>
    int(0)
  }
}