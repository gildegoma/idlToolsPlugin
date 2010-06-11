<?php

include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
$t = new lime_test(0, new lime_output_color());
/*
// Type detection
$t->is(idlString::guestStringType('simple'), 'notype', "String type detection for simple string");
$t->is(idlString::guestStringType('MyTestString'), 'camel', "String type detection for CamelCase");
$t->is(idlString::guestStringType('myTestString'), 'camel', "String type detection for CamelCase");
$t->is(idlString::guestStringType('my-test-string'), 'css', "String type detection for CSS");
$t->is(idlString::guestStringType('my_test_string'), 'underscore', "String type detection for Underscored");



$t->is(idlString::convertCamelizedToCssStyle('MyTestString'), "my-test-string", "->convertCamelizedToCssStyle() : Camelize string starting by an uppercase");
$t->is(idlString::convertCamelizedToCssStyle('myTestString'), "my-test-string", "->convertCamelizedToCssStyle() : Camelize string starting by a lowercase");
$t->is(idlString::convertCamelizedToCssStyle('myTString'), "my-t-string", "->convertCamelizedToCssStyle() : Camelize string with double uppercase");
$t->is(idlString::convertCamelizedToCssStyle('String'), "string", "->convertCamelizedToCssStyle() : String with only one word");
$t->is(idlString::convertCamelizedToCssStyle('string'), "string", "->convertCamelizedToCssStyle() : String with only lowercase");
try{idlString::convertCamelizedToCssStyle("MyString:Test24;"); $t->fail("->convertCamelizedToCssStyle: must not accept illegal chars!");}
catch (Exception $e){$t->pass("->convertCamelizedToCssStyle: raise an Exception when not allowed characters are used");}

$t->is(idlString::convertUnderScoreToCssStyle('my_test_string'), "my-test-string", "->convertUnderScoreToCssStyle() : Underscore with two underscore");
$t->is(idlString::convertUnderScoreToCssStyle('my_string'), "my-t-string", "->convertUnderScoreToCssStyle() : Underscore with two underscore");
try{idlString::convertUnderScoreToCssStyle("_my_string"); $t->fail("->convertUnderScoreToCssStyle: must not accept string that's start with underscore");}
catch (Exception $e){$t->pass("->convertUnderScoreToCssStyle: refuse string that start with under score");}
*/