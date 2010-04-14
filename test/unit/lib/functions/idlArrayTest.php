<?php

include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
$t = new lime_test(2, new lime_output_color());

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
