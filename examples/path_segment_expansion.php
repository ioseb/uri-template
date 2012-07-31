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
  "{/who}",
  "{/who,who}",
  "{/half,who}",
  "{/who,dub}",
  "{/var}",
  "{/var,empty}",
  "{/var,undef}",
  "{/var,x}/here",
  "{/var:1,var}",
  "{/list}",
  "{/list*}",
  "{/list*,path:4}",
  "{/keys}",
  "{/keys*}"
);

$uris = array();

foreach ($templates as $template) {
  $uris[$template] = uri_template($template, $data);
}

var_export($uris);

/* Result */
/*
array(
  "{/who}"          => "/fred",
  "{/who,who}"      => "/fred/fred",
  "{/half,who}"     => "/50%25/fred",
  "{/who,dub}"      => "/fred/me%2Ftoo",
  "{/var}"          => "/value",
  "{/var,empty}"    => "/value/",
  "{/var,undef}"    => "/value",
  "{/var,x}/here"   => "/value/1024/here",
  "{/var:1,var}"    => "/v/value",
  "{/list}"         => "/red,green,blue",
  "{/list*}"        => "/red/green/blue",
  "{/list*,path:4}" => "/red/green/blue/%2Ffoo",
  "{/keys}"         => "/semi,%3B,dot,.,comma,%2C",
  "{/keys*}"        => "/semi=%3B/dot=./comma=%2C"
)
*/
?>