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
    if ( $result == false ){
      throw new Exception("Impossible to create the directory $path, please check the filesystem permissions.");
    }
      
  }
    
}