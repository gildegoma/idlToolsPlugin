<?php

class idlOption extends idlFunction {
  
  /*
   * Function to merge options, if a new option is accepptable, an Exception is raise
   *  Acceptable option can be pass as a third array, if not provide, the keys of the defaults
   *  will be used
   * @param $defaults Default options
   * @param $new      New options
   * @param $allows   Allowed options
   */
  public static function merge(array $options, array $defaults, array $allows = null){
    
    // Construc the allow keys with the defaults keys and the provided
    if ($allows == null) {
      $allows = array_keys($defaults);
    }
    else {
      $allows = array_merge(array_keys($defaults), $allows); 
    }
    
    // Check for unautorized
    $unautorized = array_diff(array_keys($options), $allows);
    if (count($unautorized) > 0) {
      throw new Exception("Options ".idlArray::toString($unautorized)." are not allow.");
    }
    
    // Return the merge options
    return idlArray::merge($defaults, $options);
  }
   
  
}