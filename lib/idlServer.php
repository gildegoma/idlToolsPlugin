<?php

class idlServer {
  
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
    else
      return "UNKNOWN";
  } 
   
}