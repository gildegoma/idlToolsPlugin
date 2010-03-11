<?php

class idlServer extends idlFunction {
  
  /**
   * Return the current Operating System
   * @return string
   */
  public static function getOS() {
    if ( (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') && (php_uname('r') == "6.0" ) )
      return "VISTA";
    elseif ( (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') && (php_uname('r') == "6.1" ) )
      return "SEVEN";
    elseif ( (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') && (php_uname('r') == "5.1") )
      return "XP";
    elseif ( (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') && (php_uname('r') == "5.2") )
      return "SERVER_2003";
    else
      return "UNKNOWN";
  }

  /**
   * Return true if the script run on windows
   * @return boolean
   */
  public static function isWindows() {
  	$os = self::getOS();
  	return $os == "VISTA" || $os == "SEVEN" || $os == "XP" || $os == "SERVER_2003";
  }
  
  /**
   * Return the maximum upload size in bytes 
   * @return int
   */
  public static function getMaxUploadSize(){
    // Max upload size
    $maxSizeUpload = idlServer::getNbrOfBytesIn(ini_get('upload_max_filesize'));
    $maxSizePost = idlServer::getNbrOfBytesIn(ini_get('post_max_size'));
    $memoryLimit = idlServer::getNbrOfBytesIn(ini_get('memory_limit'));
    
    if ($memoryLimit == -1) // This means no memory limit
      return min($maxSizeUpload,$maxSizePost);
    else
      return min($maxSizeUpload,$maxSizePost,$memoryLimit);
  }
  
  
  /**
   * Return the number of byte in string like 12K, 34M, 7G
   * @param string $string
   * @return int
   */
  public static function getNbrOfBytesIn($string){
    // If it's already an int, just return 
    if (is_int($string)){
      return $string;
    }
    // Exctracxt the value from the string
    $string = trim($string);
    $unit = strtolower($string[strlen($string)-1]);
    $val = intval($string);
    switch($unit) {
      case 'g':
        $val *= 1024;
      case 'm':
        $val *= 1024;
      case 'k':
        $val *= 1024;
    }
    return $val;
  }
  
   
}