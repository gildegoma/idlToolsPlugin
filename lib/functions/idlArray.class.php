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
  
  public static function toString($array, $withKey = false){
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