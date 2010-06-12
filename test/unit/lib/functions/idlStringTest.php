<?php

include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
$t = new lime_test(35, new lime_output_color());

// Type detection
$t->is(idlString::guestStringType('simple'), 'untyped', "->guestStringType() String type detection for simple string");
$t->is(idlString::guestStringType('Simple'), 'camel', "->guestStringType() String type detection for one word CamelCase");
$t->is(idlString::guestStringType('MyTestString'), 'camel', "->guestStringType() String type detection for CamelCase");
$t->is(idlString::guestStringType('myTestString'), 'camel', "->guestStringType() String type detection for CamelCase");
$t->is(idlString::guestStringType('my404TestString'), 'camel', "->guestStringType() String type detection for CamelCase");
$t->is(idlString::guestStringType('my-test-string'), 'css', "->guestStringType() String type detection for CSS");
$t->is(idlString::guestStringType('my-404-test-string'), 'css', "->guestStringType() String type detection for CSS");
$t->is(idlString::guestStringType('my_test_string'), 'underscore', "->guestStringType() String type detection for Underscored");
$t->is(idlString::guestStringType('my_404_test_string'), 'underscore', "->guestStringType() String type detection for Underscored");
$t->is(idlString::guestStringType('404testString'), 'invalid', "->guestStringType() Make as unkown string starting by number");
$t->is(idlString::guestStringType('my_404test_string'), 'invalid', "->guestStringType() Make as unkown string with number and chars in the same word");
$t->is(idlString::guestStringType('my_404_Toto'), 'invalid', "->guestStringType() Make as unkown string with upper case and _");
$t->is(idlString::guestStringType('my-404-Toto'), 'invalid', "->guestStringType() Make as unkown string with upper case and -");

// Camelizing
//$t->is(idlString::camelize('string'), "String", "->camelize() : String with only one word");
$t->is(idlString::camelize("my-404-test-string"), 'My404TestString', "->camelize() : From css style");
$t->is(idlString::camelize("my-t-string"), 'MyTString', "->camelize() : From css style");
$t->is(idlString::camelize('my_test_string'), "MyTestString", "->camelize() : From underscored ");
try{idlString::camelize("myString_24;"); $t->fail("->camelize() : must not accept illegal chars!");}
catch (Exception $e){$t->pass("->camelize() : raise an Exception when not allowed characters are used");}

// CSS Style
$t->is(idlString::cssify('string'), "string", "->cssify() : String with only one word");
$t->is(idlString::cssify('String'), "string", "->cssify() : String with only uppercase first");
$t->is(idlString::cssify('My404TestString'), "my-404-test-string", "->cssify() : From camelCase to css style");
$t->is(idlString::cssify('myTString'), "my-t-string", "->cssify() : From camelCase with double uppercase to css style");
$t->is(idlString::cssify('my_test_string'), "my-test-string", "->cssify() : From underscored to css style");
try{idlString::cssify("myString_24;"); $t->fail("->cssify() : must not accept illegal chars!");}
catch (Exception $e){$t->pass("->cssify() : raise an Exception when not allowed characters are used");}

// Underscored 
$t->is(idlString::underscorify('string'), "string", "->underscorify() : String with only one word");
$t->is(idlString::underscorify('String'), "string", "->underscorify() : String with only uppercase first");
$t->is(idlString::underscorify('My404TestString'), "my_404_test_string", "->underscorify() : From camelCase to css style");
$t->is(idlString::underscorify('myTString'), "my_t_string", "->underscorify() : From camelCase with double uppercase to css style");
$t->is(idlString::underscorify('my-test-string'), "my_test_string", "->underscorify() : From underscored to css style");
try{idlString::cssify("myString_24;"); $t->fail("->underscorify() : must not accept illegal chars!");}
catch (Exception $e){$t->pass("->underscorify() : raise an Exception when not allowed characters are used");}

// Deprecated test, could be remove when removing convertCamelizedToCssStyle
$t->is(idlString::convertCamelizedToCssStyle('MyTestString'), "my-test-string", "->convertCamelizedToCssStyle() : Camelize string starting by an uppercase"); 
$t->is(idlString::convertCamelizedToCssStyle('myTestString'), "my-test-string", "->convertCamelizedToCssStyle() : Camelize string starting by a lowercase"); 
$t->is(idlString::convertCamelizedToCssStyle('myTString'), "my-t-string", "->convertCamelizedToCssStyle() : Camelize string with double uppercase"); 
$t->is(idlString::convertCamelizedToCssStyle('String'), "string", "->convertCamelizedToCssStyle() : String with only one word"); 
$t->is(idlString::convertCamelizedToCssStyle('string'), "string", "->convertCamelizedToCssStyle() : String with only lowercase"); 
try{idlString::convertCamelizedToCssStyle("MyString:Test24;"); $t->fail("->convertCamelizedToCssStyle() : must not accept illegal chars!");} 
catch (Exception $e){$t->pass("->convertCamelizedToCssStyle() : raise an Exception when not allowed characters are used");} 

