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
  "{#var}",
  "{#hello}",
  "{#half}",
  "foo{#empty}",
  "foo{#undef}",
  "{#x,hello,y}",
  "{#path,x}/here",
  "{#path:6}/here",
  "{#list}",
  "{#list*}",
  "{#keys}",
  "{#keys*}"
);

$uris = array();

foreach ($templates as $template) {
  $uris[$template] = uri_template($template, $data);
}

var_export($uris);

/* Result */
/*
array(
  "{#var}"         => "#value",
  "{#hello}"       => "#Hello%20World!",
  "{#half}"        => "#50%25",
  "foo{#empty}"    => "foo#",
  "foo{#undef}"    => "foo",
  "{#x,hello,y}"   => "#1024,Hello%20World!,768",
  "{#path,x}/here" => "#/foo/bar,1024/here",
  "{#path:6}/here" => "#/foo/b/here",
  "{#list}"        => "#red,green,blue",
  "{#list*}"       => "#red,green,blue",
  "{#keys}"        => "#semi,;,dot,.,comma,,",
  "{#keys*}"       => "#semi=;,dot=.,comma=,"
)
*/
?>