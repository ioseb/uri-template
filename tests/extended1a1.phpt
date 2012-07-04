--TEST--
uri_template() level 4 expansion - Additional Examples 1
--FILE--
<?php

$data = array(
  "id"           => "person",
  "token"        => "12345",
  "fields"       => array("id", "name", "picture"),
  "format"       => "json",
  "q"            => "URI Templates",
  "page"         => "5",
  "lang"         => "en",
  "geocode"      => array("37.76","-122.427"),
  "first_name"   => "John",
  "last.name"    => "Doe", 
  "Some%20Thing" => "foo",
  "number"       => 6,
  "long"         => 37.76,
  "lat"          => -122.427
);

$templates = array(
  "{/id*}" => "/person",
  "{/id*}{?fields,first_name,last.name,token}" => "/person?fields=id,name,picture&first_name=John&last.name=Doe&token=12345",
  "/search.{format}{?q,geocode,lang,locale,page,result_type}" => "/search.json?q=URI%20Templates&geocode=37.76,-122.427&lang=en&page=5",
  "/test{/Some%20Thing}" => "/test/foo",
  "/set{?number}" => "/set?number=6",
  "/loc{?long,lat}" => "/loc?long=37.76&lat=-122.427",
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
array(6) {
  [0]=>
  array(2) {
    ["result"]=>
    string(7) "/person"
    ["state"]=>
    int(0)
  }
  [1]=>
  array(2) {
    ["result"]=>
    string(72) "/person?fields=id,name,picture&first_name=John&last.name=Doe&token=12345"
    ["state"]=>
    int(0)
  }
  [2]=>
  array(2) {
    ["result"]=>
    string(68) "/search.json?q=URI%20Templates&geocode=37.76,-122.427&lang=en&page=5"
    ["state"]=>
    int(0)
  }
  [3]=>
  array(2) {
    ["result"]=>
    string(9) "/test/foo"
    ["state"]=>
    int(0)
  }
  [4]=>
  array(2) {
    ["result"]=>
    string(13) "/set?number=6"
    ["state"]=>
    int(0)
  }
  [5]=>
  array(2) {
    ["result"]=>
    string(28) "/loc?long=37.76&lat=-122.427"
    ["state"]=>
    int(0)
  }
}