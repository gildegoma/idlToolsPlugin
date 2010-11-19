<?php

include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
$t = new lime_test(2, new lime_output_color());

// Test the decode
$t->is(idlJson::decode('[{"id":3},{"id":4,"name":"toto"}]'), array(array('id'=>3),array('id'=>4,'name'=>'toto')), "->decode() Decode return the corresponding assoc array");
try {idlJson::decode('toto'); $t->fail("->decode() fail to detected invalid json data");}
catch (Exception $e) {$t->pass("->decode() detected invalid json data");}