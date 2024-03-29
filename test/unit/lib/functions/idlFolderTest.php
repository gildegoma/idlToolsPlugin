<?php

include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
include_once(dirname(__FILE__).'/../../../bootstrap/getTempDir.php');

$t = new lime_test(4, new lime_output_color());

$newFolder = $tempDir.DIRECTORY_SEPARATOR.'toto';

// Test folder creation 
idlFolder::create($newFolder);
$t->ok(is_dir($tempDir), "create() Folder creation is working");

// Test that create on a existing path is failling
try {
  idlFolder::create($tempDir);
  $t->fail("create() Not throwing on error if the folder already exist");
}
catch (Exception $e) {
  $t->pass("create() Refuse to create on a existing path");
}


// Test remove a simple folder
idlFolder::remove($newFolder);
$t->ok(! is_dir($newFolder), "remove() Simple folder has been remove");

// Test recursive remove 
idlFolder::create($newFolder);
idlFolder::remove($tempDir);
$t->ok(! is_dir($tempDir), "remove() Recursive folder remove");

