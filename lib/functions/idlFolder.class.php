<?php

class idlFolder extends idlFunction {
	
	/**
	 * Create a folder for the specified path, if folder already exist, function throw an error
	 * @param string  $path      The path where to create the folder
	 * @param bool    $writable  By default permission will be set to 0777
	 */
  public static function create($path, $permissions = 0777){
  	
  	// Check if already exist
  	if (is_dir($path)){
      throw new Exception("Impossible to create the directory $path, already exist");
  	}
   
  	// Save the current mask, for restoring afther the creation
    $current_umask = umask(0000);
    
    // Create the dir in recursive mode
    $result = @mkdir($path, $permissions, true);
    umask($current_umask);
    
    // Throw an exception if the creation fail
    if ( $result == false || ! is_dir($path) ){
      throw new Exception("Impossible to create the directory $path, please check the filesystem permissions.");
    }
      
  }
  
  
  /**
   * Delete a directory and all of its contents.
   *  Inspiered from the FileSystem.php from phing: 
   *  /lib/symfony/vendor/phing/system/io/FileSystem.php
   * 
   * @param string $path Path of directory to delete.
   */ 
  public static function remove($dir){
    global $php_errormsg; // Access to the error message
        
    $handle = @opendir($dir);
    if (!$handle) {
      throw new Exception("Cannot access directory [$dir]. $php_errormsg");
    }

    while (false !== ($entry = @readdir($handle))) {
      
      // Ignore . and ..
      if ($entry == '.' || $entry == '..') continue;
        
      // Add / if it isn't already the last char.
      if (strpos(strrev($dir), DIRECTORY_SEPARATOR) === 0) {
        $next_entry = $dir . $entry;
      } else {
        $next_entry = $dir . DIRECTORY_SEPARATOR . $entry;
      }
      
      // Remove element  
      if (@is_dir($next_entry)) {
        self::remove($next_entry);
      }
      else { 
        if (false === @unlink($next_entry)) {
          throw new Exception("Cannot remove [$next_entry]. $php_errormsg");
        }
      }
      
    }
    @closedir($handle);
    
    // Remove itself
    if (false === @rmdir($dir)) {
      $msg = "Cannot remove directory $dir. ($php_errormsg)";
      throw new Exception($msg);
    }
            
  }
    
}