--TEST--
uri_template() copy literals, compare encoded literal chars and encoded variable value
--FILE--
<?php

$utf8str = 'სიმბოლო';
$template = '{utf8str}';
$result = array(
	uri_template($utf8str, array()),
	uri_template($template, array('utf8str' => $utf8str))
);

var_dump($result);
?>
--EXPECT--
array(2) {
  [0]=>
  string(63) "%E1%83%A1%E1%83%98%E1%83%9B%E1%83%91%E1%83%9D%E1%83%9A%E1%83%9D"
  [1]=>
  string(63) "%E1%83%A1%E1%83%98%E1%83%9B%E1%83%91%E1%83%9D%E1%83%9A%E1%83%9D"
}