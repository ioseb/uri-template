--TEST--
uri_template() copy pct-encoded triplets and expand variables with utf8 strings
--FILE--
<?php

$input = '%25x21%20%2F%20%25x23-{utf8str}-24%20%2F%20%25x26%20%2F%20%25x28-3B%20%2F%20%25x3D%20%2F%20%25x3F-{utf8str}-5B';
$result = uri_template($input, array('utf8str' => 'სიმბოლო'));

var_dump($result);
?>
--EXPECT--
string(218) "%25x21%20%2F%20%25x23-%E1%83%A1%E1%83%98%E1%83%9B%E1%83%91%E1%83%9D%E1%83%9A%E1%83%9D-24%20%2F%20%25x26%20%2F%20%25x28-3B%20%2F%20%25x3D%20%2F%20%25x3F-%E1%83%A1%E1%83%98%E1%83%9B%E1%83%91%E1%83%9D%E1%83%9A%E1%83%9D-5B"