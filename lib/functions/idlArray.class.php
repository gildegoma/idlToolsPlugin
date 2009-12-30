<?php

class idlArray extends idlFunction {
  
  
  /*
   * Function to merge two table deeply
   * @param $original First array to merge
   * @param $new Second array to merge
   */
  public static function arrayMerge($original, $new){
    foreach($new as $key => $newValue){
      // If there is a similar key, and both are arrays, so we merge the sub array
      if ( array_key_exists($key,$original) && is_array($newValue) && is_array($original[$key]) ){
        $new[$key] = self::arrayMerge($original[$key],$newValue);
      }
    }
    return array_merge($original, $new);
  }
  
  
  /**
   * toString function convert a php array to a readable string 
   * @param array $array The array to convert
   * @param boolean $withKey If set to true, the keys of the array will be display
   * @return string
   */
  public static function toString($array, $withKey = false){

  	// Treat only array
  	if ( ! isset($array) || ! is_array($array))
  	  throw new Exception("The function idlArray::toString required an array as first parameter");
  	
    $text = "[";
    foreach ($array as $key => $value){
      // Add a value separator
      if ($text[strlen($text)-1] != "["){
        $text .= ",";
      }
      // Add the key
      if ($withKey){
        $text .= $key . "=>";
      }
      // Add the value ( this is recusive if need)
      $text .= (is_array($value) ? self::toString($value, $withKey) : $value);
    }
    return $text."]";;
  }
}