<?php

include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
$t = new lime_test(6, new lime_output_color());

$default = array('op1' => 1, 'op2' => 2, 'op3' => 3);

// Test basic merge
$merged = idlOption::merge(array('op3'=>4), $default);
$t->ok($merged['op3']==4, "merge() Allow to override default option");

// Test the extra option refused
try {
  idlOption::merge(array('op4'=>4), $default);
  $t->fail("merge() Accept a none default option");
}
catch (Exception $e) {
  $t->pass("merge() Refuse a non default option");
}

// Test the extra option accepted due to the allow params
try {
  idlOption::merge(array('op4'=>4), $default, array('op4'));
  $t->pass("merge() Accept a none default option if it has been explicitely describe in the allow param ");
}
catch (Exception $e) {
  $t->fail("merge() Refuse a non default option, but that was explicitely autorized");
}


// Test validate() with normal things
$paramList = array('op1', 'op2', 'op3');
try {
  idlOption::validate($paramList, array('*op1', 'op2', 'op3'));
  $t->pass('->validate() succeeded');
}
catch(Exception $e){
  $t->fail("->validate() does not succeeded: ".$e->getMessage());
}

// Test validate() with with 1 extra parameter that is nor optional neither mandatory
$paramList = array('op1', 'op2', 'op3', 'op4');
try {
  idlOption::validate($paramList, array('*op1', 'op2', 'op3'));
  $t->fail('->validate() must not succeed');
}
catch(Exception $e){
  $t->pass('->validate() did not succeed because 1 extra parameter that is nor optional neither mandatory was given');
}

// Test validate() with 1 parameter that is mandatory but not given
$paramList = array('op1', 'op2');
try {
  idlOption::validate($paramList, array('*op1', 'op2', 'op3'));
  $t->fail('->validate() must not succeed');
}
catch(Exception $e){
  $t->pass('->validate() did not succeed because 1 parameter that is mandatory is not given');
}

