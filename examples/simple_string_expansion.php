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
  "{var}",
  "{hello}",
  "{half}",
  "O{empty}X",
  "O{undef}X",
  "{x,y}",
  "{x,hello,y}",
  "?{x,empty}",
  "?{x,undef}",
  "?{undef,y}",
  "{var:3}",
  "{var:30}",
  "{list}",
  "{list*}",
  "{keys}",
  "{keys*}"
);

$uris = array();

foreach ($templates as $template) {
  $uris[$template] = uri_template($template, $data);
}

var_export($uris);

/* Result */
/*
array (
  '{var}'       => 'value',
  '{hello}'     => 'Hello%20World%21',
  '{half}'      => '50%25',
  'O{empty}X'   => 'OX',
  'O{undef}X'   => 'OX',
  '{x,y}'       => '1024,768',
  '{x,hello,y}' => '1024,Hello%20World%21,768',
  '?{x,empty}'  => '?1024,',
  '?{x,undef}'  => '?1024',
  '?{undef,y}'  => '?768',
  '{var:3}'     => 'val',
  '{var:30}'    => 'value',
  '{list}'      => 'red,green,blue',
  '{list*}'     => 'red,green,blue',
  '{keys}'      => 'semi,%3B,dot,.,comma,%2C',
  '{keys*}'     => 'semi=%3B,dot=.,comma=%2C',
)
*/
?>