<?php

include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
$t = new lime_test(21, new lime_output_color());

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

// Test the getLast
$arr1 = $arr2 = array('key1' => 1, 'key2' => 2, 'key3' => 3);
$t->ok(idlArray::getLast($arr1)==3,"getLast() return the last value");
$t->ok($arr1==$arr2,"getLast() doesn't alter the array");
try {idlArray::getLast(array()); $t->fail("getLast() accept empty array");}
catch (Exception $e) {$t->pass("getLast() refuse empty array");}

// Test the insertIn
$arr = array('toto'=>2);
idlArray::insertIn($arr,'tata',3);
$t->is($arr,array('toto'=>2, 'tata'=>3),"insertIn() Insert in an array with a simple key");
$arr = array('toto'=>2);
idlArray::insertIn($arr,null,3);
$t->is($arr,array(0=>3,'toto'=>2),"insertIn() Insert in an array with the null key");
$arr = array('toto'=>2);
idlArray::insertIn($arr,array('tata','titi'),3);
$t->is($arr,array('toto'=>2, 'tata'=>array('titi'=>3)),"insertIn() Insert in an array with two keys");
$arr = array(0=>2);
idlArray::insertIn($arr,array(null,null),3);
$t->is($arr,array(0=>2, 1=>array(0=>3)),"insertIn() Insert in an array with a two null keys");
$arr = array('toto'=>2);
try {idlArray::insertIn($arr,'toto',3); $t->fail("insertIn() accept position that are already used");}
catch (Exception $e) {$t->pass("insertIn() refuse position that are already used");}
try {idlArray::insertIn($arr,array('toto', null),3); $t->fail("insertIn() accept subkey that are not an array");}
catch (Exception $e) {$t->pass("insertIn() refuse subkey that are not array");}
$arr = array();
idlArray::insertIn($arr,array('toto','tata'),3);
$t->is($arr, array('toto'=>array('tata'=>3)),"insertIn() Insert in an empty array");
$arr = array();
idlArray::insertIn($arr,array('toto','tata','titi', null),3);
$t->is($arr, array('toto'=>array('tata'=>array('titi'=>array(3)))),"insertIn() Try with 4 levels of insert");

