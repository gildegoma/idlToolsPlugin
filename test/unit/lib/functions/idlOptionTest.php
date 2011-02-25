<?php

include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
$t = new lime_test(9, new lime_output_color());

// Test ->merge()
$default = array('op1' => 1, 'op2' => 2, 'op3' => 3);
$merged = idlOption::merge(array('op3'=>4), $default);
$t->ok($merged['op3']==4, "merge() Allow to override default option");
try {
  idlOption::merge(array('op4'=>4), $default);
  $t->fail("merge() Accept a none default option");
}
catch (Exception $e) {
  $t->pass("merge() Refuse a non default option");
}
try {
  idlOption::merge(array('op4'=>4), $default, array('op4'));
  $t->pass("merge() Accept a none default option if it has been explicitely describe in the allow param ");
}
catch (Exception $e) {
  $t->fail("merge() Refuse a non default option, but that was explicitely autorized");
}


// Test validate()
try {  $t->ok(idlOption::validate(array(), array('op1')), '->validate() always succeeded when no options provide'); }
catch(Exception $e){  $t->fail('->validate() throw unexpected Exception: '.$e->getMessage()); }

try {  idlOption::validate(array('opt1'=>'toto'), array()); $t->fail('->validate() $allows var should not be empty'); }
catch(Exception $e){  $t->pass('->validate() $allows var should not be empty'); }

try {  $t->ok(idlOption::validate(array('op1'=>'toto'), array('op1', 'op2')),'->validate() succeeded when opt provide is in the allow list'); }
catch(Exception $e){  $t->fail('->validate() throw unexpected Exception: '.$e->getMessage()); }

try {  idlOption::validate(array('op3'=>'toto'), array('op1', 'op2')); $t->fail('->validate() detection of extra parameters fail'); }
catch(Exception $e){  $t->pass('->validate() throw Exception when a extra parameters is detected'); }

try {  $t->ok(idlOption::validate(array('op1'=>'toto'), array('*op1', 'op2')),'->validate() succeeded when mandatory opt is provide'); }
catch(Exception $e){  $t->fail("->validate() throw unexpected Exception: ".$e->getMessage()); }

try {  idlOption::validate(array('op1'=>'toto'), array('op1', '*op2')); $t->fail('->validate() fail when mandatory opt is not provide'); }
catch(Exception $e){  $t->pass('->validate() throw Exception when a mandatory parameter is missing;'); }