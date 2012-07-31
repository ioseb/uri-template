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
  "{;who}",
  "{;half}",
  "{;empty}",
  "{;v,empty,who}",
  "{;v,bar,who}",
  "{;x,y}",
  "{;x,y,empty}",
  "{;x,y,undef}",
  "{;hello:5}",
  "{;list}",
  "{;list*}",
  "{;keys}",
  "{;keys*}"
);

$uris = array();

foreach ($templates as $template) {
  $uris[$template] = uri_template($template, $data);
}

var_export($uris);

/* Result */
/*
array(
  "{;who}"         => ";who=fred",
  "{;half}"        => ";half=50%25",
  "{;empty}"       => ";empty",
  "{;v,empty,who}" => ";v=6;empty;who=fred",
  "{;v,bar,who}"   => ";v=6;who=fred",
  "{;x,y}"         => ";x=1024;y=768",
  "{;x,y,empty}"   => ";x=1024;y=768;empty",
  "{;x,y,undef}"   => ";x=1024;y=768",
  "{;hello:5}"     => ";hello=Hello",
  "{;list}"        => ";list=red,green,blue",
  "{;list*}"       => ";list=red;list=green;list=blue",
  "{;keys}"        => ";keys=semi,%3B,dot,.,comma,%2C",
  "{;keys*}"       => ";semi=%3B;dot=.;comma=%2C"
)
*/
?>