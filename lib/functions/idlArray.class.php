<?php

class idlArray extends idlFunction {
  
  /**
   * Function to merge two table deeply
   * @param $original First array to merge
   * @param $new Second array to merge
   */
  public static function merge($original, $new){
    foreach($new as $key => $newValue){
      // If there is a similar key, and both are arrays, so we merge the sub array
      if ( array_key_exists($key,$original) && is_array($newValue) && is_array($original[$key]) ){
        $new[$key] = self::merge($original[$key],$newValue);
      }
    }
    return array_merge($original, $new);
  }
  
  /**
   * @deprecated Use merge insted
   * @see idlArray::merge
   */
  public static function arrayMerge($original, $new){
    return self::merge($original,$new);
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
  
  /**
   * Extract a value from an array at the given key, or return the default value if the key doesn't exist
   * @param array $array
   * @param string $key
   * @param mixed $default
   * @return mixed
   */
  public static function get($array, $key, $default){
  	return isset($array[$key]) ? $array[$key] : $default;
  }
  
  /**
   * Insert a value in an array at the given position
   * @param array $array  The array where to insert the value
   * @param int   $pos    The position where to put the value
   * @param mixed $val    The value to insert
   * @return array        The updated array
   */
  public static function insert($array,$pos,$val){
    $array2 = array_splice($array,$pos);
    $array[] = $val;
    $array = array_merge($array,$array2);
    return $array;
  }
  
  /**
   * @deprecated Use insert insted
   * @see idlArray::insert
   */
  public static function arrayInsert($array,$pos,$val){
    return self::insert($array,$pos,$val);
  }
  
  
  /**
   * Return the last element of an array, without removing it from the array, and 
   *  without moving the internal pointer
   * @param array $array
   * @return mixed
   */
  public static function getLast($array){
    if (!is_array($array) || empty($array)){
      throw new Exception("The provided variable is not an array with a value inside");
    }
    $keys = array_keys($array);
    return $array[end($keys)];
  }
  
  
  /**
   * Insert a value in a sub array, if the sub array does exist, it's
   *  automatically created 
   * @param $array  Main array
   * @param $key    The key of the sub array
   * @param $value  The value to insert
   * @param $subKey If provide, this will be the insertion key, by default it's
   *                 only happend to the end
   */
  public static function insertIn(&$array, $key, $value, $subKey = null){
    if (!isset($array[$key])){
      $array[$key] = array();
    }
    if (!is_array($array[$key])){
      throw new Exception("The key already exist, but it's not an array");
    }
    if ($subKey == null){
      $array[$key][] = $value;
    }
    else {
      $array[$key][$subKey] = $value;
    } 
  }
  
  
}