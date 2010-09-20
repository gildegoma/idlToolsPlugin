<?php

include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
$t = new lime_test(6, new lime_output_color());

$t->is(idlDate::toTS("1980-11-08"), 342486000, "toTS() Convert date to TS");
$t->is(idlDate::toTS("1515-1-1"), -14358474000, "toTS() Convert big date to TS");
$t->is(idlDate::toTS("6099-12-31"), 130330162800, "toTS() Convert small date to TS");
$t->ok(is_string(idlDate::toTS("2009-12-31")), "toTS() By default, return a string");
$t->ok(is_int(idlDate::toTS("2009-12-31", true)), "toTS() Can return int if requested");

// Test about convert to int on 32bit system
if (PHP_INT_MAX==2147483647){
  try{idlDate::toTS("1515-1-1", true); $t->fail("->toTS() Fail to raise exception if the int min/max value are reach");}
  catch (Exception $e){$t->pass("->toTS() Request int conversion raise exception if the int min/max value are reach");}
}
else {
  $t->skip('Convert out of bound, no testable on 64bit system');
}
