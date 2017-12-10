<?php
/*
* the declaration of a string (data type)
*
* 1. can use double quotes or single quotes
*
* the variable can be parsed in the double quotes, and all the escape characters can be used in the double quotes

* 2. < < <

* segment, match, find, replace
*
*
* feature: if it is other types of data, you can also use a string processing function. The other types are automatically converted to a self - character string and reprocessed
*
*
* string can be accessed to each character by the subscript, like an array. (but not array array can also be accessed by members of the subscript {})
*
* in addition to English characters, there are Chinese
 */
//	echo count("abc");


	define("one", "two");

	$int = array("one"=>100, "two"=>200);


	class Demo {
		var $one=100;
	
	}

	$d = new Demo;





	echo "aaaaaaaaaaaa{$d->one}aaaaaaaaaaaaaa<br>";
	echo "aaaaaaaaaaaa{$int["one"]}aaaaaaaaaaaaaa<br>";
