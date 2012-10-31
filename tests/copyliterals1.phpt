--TEST--
uri_template() copy pct-encoded triplets
--FILE--
<?php

$input = '%25x21%20%2F%20%25x23-24%20%2F%20%25x26%20%2F%20%25x28-3B%20%2F%20%25x3D%20%2F%20%25x3F-5B';
$result = uri_template($input, array());

var_dump($result);
?>
--EXPECT--
string(90) "%25x21%20%2F%20%25x23-24%20%2F%20%25x26%20%2F%20%25x28-3B%20%2F%20%25x3D%20%2F%20%25x3F-5B"