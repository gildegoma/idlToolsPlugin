<?php

include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
$t = new lime_test(3, new lime_output_color());

$default = array('op1' => 1, 'op2' => 2, 'op3' => 3);

// Test basic merge
$merged = idlOption::merge($default, array('op3'=>4));
$t->ok($merged['op3']==4, "merge() Allow to override default option");

// Test the extra option refused
try {
  idlOption::merge($default, array('op4'=>4));
  $t->fail("merge() Accept a none default option");
}
catch (Exception $e) {
  $t->pass("merge() Refuse a non default option");
}

// Test the extra option accepted due to the allow params
try {
  idlOption::merge($default, array('op4'=>4),array('op4'));
  $t->pass("merge() Accept a none default option if it has been explicitely describe in the allow param ");
}
catch (Exception $e) {
  $t->fail("merge() Refuse a non default option, but that was explicitely autorized");
}
