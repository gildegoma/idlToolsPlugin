<?php

include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
$t = new lime_test(2, new lime_output_color());

$string1 = "MyStringTest";
$cssString1 = idlString::convertCamelizedToCssStyle($string1);
$t->is($cssString1, "my-string-test", "-> convertCamelizedToCssStyle : String converted to css class");

$string2 = "MyString:Test24;";
try{$cssString2 = idlString::convertCamelizedToCssStyle($string2); $t->fail("-> convertCamelizedToCssStyle: must not accept illegal chars!");}
catch (Exception $e){$t->pass("-> convertCamelizedToCssStyle: raise an Exception when not allowed characters are used");}
