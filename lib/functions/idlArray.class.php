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
    if ( !self::isArray($array) ) {
      throw new Exception("The function idlArray::toString required an array as first parameter");
    }
    
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
      if (self::isArray($value)){
        $text .= self::toString($value, $withKey);
      }
      elseif (is_string($value)) {
        $text .= '"'.$value.'"';
      }
      elseif (is_object($value)) {
        $text .= 'object{'.get_class($value);
        if (method_exists($value, '__toString')){
          $text .= '('.$value.')';
        }
        $text .= '}';
      }
      elseif ($value == null) {
        $text .= 'null';
      }
      else {
        $text .= $value;
      }
    }
    return $text."]";;
  }
  
  /**
   * Extract a value from an array at the given key, or return the default value if the key doesn't exist
   * If no default value are provide, the method could return an exception
   * @param array $array
   * @param string $key
   * @param mixed $default
   * @return mixed
   */
  public static function get($array, $key, $default = null){
    if ( !self::isArray($array)  )
      throw new Exception("Array must be provide in idlArray::get()");
    if ( isset($array[$key]) )
      return $array[$key];
    elseif ( isset($default) )
      return $default;
    else
      throw new Exception("There is no key [$key] in the requested array:".idlArray::toString($array, true));
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
   * Insert a value in a sub array
   * @param $array      Main array
   * @param $position   Accept a key or an array of keys. The key can be null, and so 
   *                     the value is added to the end of the array
   * @param $value      The value to insert
   */
  public static function insertIn(&$array, $position, $value){
    
    // Position can be an array, if it's not, let's put in
    $keys = is_array($position) ? $position : array($position);
    
    // Extract the first key
    $key = array_shift($keys);
    
    // If there is no more keys, insert the value
    if (count($keys) == 0){
      if ($key==null){
        $array[] = $value;
      }
      else {
        if (isset($array[$key])){
          throw new Exception("The key '$key' already exist, insert fail");
        }
        $array[$key] = $value;
      }
    }
    
    // Recursively insert inside a sub array
    else {
      if ($key==null){
        $array[] = array();
        end($array);
        $key = key($array);
      }
      else {
        if (isset($array[$key])){
          if (!self::isArray($array[$key])){
            throw new Exception("The key '$key' already exist, but it's not an array");
          }
        }
        else {
          $array[$key] = array();
        }
      }  
      self::insertIn($array[$key], $keys, $value);
    }
  }
  
  /**
   * Remove all the specified values from the given array
   * @param array $array
   * @param mixed $values
   * @return array
   */
  public static function removeValues($array, $values){
    if (!self::isArray($values)){
      $values = array($values);
    }
    foreach ($array as $key => $value){
      if (in_array($value, $values)){
        unset($array[$key]);
      }
    }
    return $array;
  }
  
  /**
   * The traditionnal function is_array is retruning false when we test an object with ArrayAccess interface
   *  this one, accept ArrayAccess interface, so accept also sfOutputEscaperArrayDecorator
   * @param mixed any $var
   */
  public static function isArray($var){
    return is_array($var) || $var instanceof ArrayAccess;
  }
  
}