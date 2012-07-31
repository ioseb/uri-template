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
  "{.who}",
  "{.who,who}",
  "{.half,who}",
  "www{.dom*}",
  "X{.var}",
  "X{.empty}",
  "X{.undef}",
  "X{.var:3}",
  "X{.list}",
  "X{.list*}",
  "X{.keys}",
  "X{.keys*}",
  "X{.empty_keys}",
  "X{.empty_keys*}"
);

$uris = array();

foreach ($templates as $template) {
  $uris[$template] = uri_template($template, $data);
}

var_export($uris);

/* Result */
/*
array(
  "{.who}"          => ".fred",
  "{.who,who}"      => ".fred.fred",
  "{.half,who}"     => ".50%25.fred",
  "www{.dom*}"      => "www.example.com",
  "X{.var}"         => "X.value",
  "X{.empty}"       => "X.",
  "X{.undef}"       => "X",
  "X{.var:3}"       => "X.val",
  "X{.list}"        => "X.red,green,blue",
  "X{.list*}"       => "X.red.green.blue",
  "X{.keys}"        => "X.semi,%3B,dot,.,comma,%2C",
  "X{.keys*}"       => "X.semi=%3B.dot=..comma=%2C",
  "X{.empty_keys}"  => "X",
  "X{.empty_keys*}" => "X"
)
*/
?>