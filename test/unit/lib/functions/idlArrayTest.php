<?php

include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
$t = new lime_test(10, new lime_output_color());

$arr1 = array('key1' => 1, 'key2' => 2, 'key3' => 3);
$arr2 = array('key2' => 4);
$arrMerged = $arr1;
$arrMerged['key2'] = 4;

// Test basic merge
$arr3 = idlArray::merge($arr1, $arr2);
$t->ok($arr3==$arrMerged, "merge() return a new array with first array values overrided by second array values");

// Test deep merge
$arr1['sub'] = $arr1;
$arr2['sub'] = $arr2;
$arr3 = idlArray::merge($arr1, $arr2);
$t->ok($arr3['sub']==$arrMerged, "merge() deep merge is working");

// Test get value method
$t->ok(idlArray::get($arr1,'key1', 'toto')==1, "get() return the value if the key exist");
$t->ok(idlArray::get($arr1,'key9', 'toto')=='toto', "get() return the default value when the key is absent");

// Test of the toString function
$t->ok(idlArray::toString($arr1)=="[1,2,3,[1,2,3]]", "toString() convert an array without key is working");
$t->ok(idlArray::toString($arr1, true)=="[key1=>1,key2=>2,key3=>3,sub=>[key1=>1,key2=>2,key3=>3]]", "toString() convert an array with key is working");

try {idlArray::toString("toto"); $t->fail("toString() Accept a string insteat of an array");}
catch (Exception $e) {$t->pass("toString() Refuse to convert a string");}

// Test the insert
$t->ok(idlArray::insert(array(2,3),0,1)==array(1,2,3), "insert() Insert at the beginning");
$t->ok(idlArray::insert(array(1,3),1,2)==array(1,2,3), "insert() Insert in the middle");
$t->ok(idlArray::insert(array(1,2),2,3)==array(1,2,3), "insert() Insert at the end");

