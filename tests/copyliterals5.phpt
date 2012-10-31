--TEST--
uri_template() copy url - ignore %21 tripplet
--FILE--
<?php

$input = 'http://foo.com/baz?bar=bam_%21';
$result = uri_template($input, array());

var_dump($result);
?>
--EXPECT--
string(30) "http://foo.com/baz?bar=bam_%21"