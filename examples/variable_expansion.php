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
  '{count}',
  '{count*}',
  '{/count}',
  '{/count*}',
  '{;count}',
  '{;count*}',
  '{?count}',
  '{?count*}',
  '{&count*}',
);

$uris = array();

foreach ($templates as $template) {
  $uris[$template] = uri_template($template, $data);
}

var_export($uris);

/* Result */
/*
array (
  '{count}'   => 'one,two,three',
  '{count*}'  => 'one,two,three',
  '{/count}'  => '/one,two,three',
  '{/count*}' => '/one/two/three',
  '{;count}'  => ';count=one,two,three',
  '{;count*}' => ';count=one;count=two;count=three',
  '{?count}'  => '?count=one,two,three',
  '{?count*}' => '?count=one&count=two&count=three',
  '{&count*}' => '&count=one&count=two&count=three',
)
*/
?>