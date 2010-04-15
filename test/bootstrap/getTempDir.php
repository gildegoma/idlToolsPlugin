<?php
// Create a empty temp dir for filesystem test purpose
// Then the variable $tempDir is availiable in the test

$tempDir = dirname(__FILE__).'/../temp';

// Remove if already exist
if (is_dir($tempDir)){
  rmdir($tempDir.DIRECTORY_SEPARATOR.'toto'); // Potentially created in the idlFolder test
  rmdir($tempDir);
}

mkdir($tempDir, 0777, true);