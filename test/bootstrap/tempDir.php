<?php
// Create a empty temp dir for filesystem test purpose
$tempDir = dirname(__FILE__).'/../temp';

// Remove if already exist
if (is_dir($tempDir)) 
  unlink($tempDir);
  
mkdir($tempDir, 0777, true);