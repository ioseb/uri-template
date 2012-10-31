--TEST--
uri_template() copy url - ignore ! char
--FILE--
<?php

$input = 'http://foo.com/baz?bar=bam_!';
$result = uri_template($input, array());

var_dump($result);
?>
--EXPECT--
string(28) "http://foo.com/baz?bar=bam_!"