--TEST--
uri_template() level 4 expansion - Additional Examples 2
--FILE--
<?php

$data = array(
  "id"      => array("person","albums"),
  "token"   => "12345",
  "fields"  => array("id", "name", "picture"),
  "format"  => "atom",
  "q"       => "URI Templates",
  "page"    => "10",
  "start"   => "5",
  "lang"    => "en",
  "geocode" => array("37.76","-122.427")
);

$templates = array(
  "{/id*}" => "/person/albums",
  "{/id*}{?fields,token}" => "/person/albums?fields=id,name,picture&token=12345"
);

$out = array();

foreach ($templates as $tpl => $expect) {
  $result = NULL;
  uri_template($tpl, $data, $result);
  unset($result['expressions']);
  $out[] = $result;
}

var_dump($out);
?>
--EXPECT--
array(2) {
  [0]=>
  array(2) {
    ["result"]=>
    string(14) "/person/albums"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(49) "/person/albums?fields=id,name,picture&token=12345"
    ["state"]=>
    int(0)
  }
}