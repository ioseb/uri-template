URI Template PHP Extension
============

PHP extension implementation of RFC-6570 in C - http://tools.ietf.org/html/rfc6570

Usage examples (see unit tests for more examples)
--------------------------------------------------

```php
	$data = array(
		"query"  => "mycelium",
	       "number" => 100
	);
  
	$result = uri_template('http://www.example.com/foo{?query,number}', $data);

	print_r($result);
	
	// Array
	// (
	//     [result] => http://www.example.com/foo?query=mycelium&number=100
	//     [state] => 0
	// )
```