<?php

/**
 * idlArrayObject is an utility to simplify the construction of an Object when ou have an associative
 *  array for initialization
 * 
 * You can simply pass the array to the contructor of your object and set what to do with every key in the 
 *  protected variable $setAccess
 *  
 * In the opposite side, you can define arrayAccess getter in the protected variable $getAccess
 * @author david
 *
 */
abstract class idlArrayObject implements ArrayAccess{

	/**
   * Key accessible by the ArrayAccess, must be override in sub class
   */  
  protected $getAccess = array();
  protected $setAccess = array();
  
  
  /**
   * Auto construct an object with an associative array, each set key must have been define
   *  or this will throw an exception
   * @param array $data must be an associative array
   */
  public function __construct($data){
    
    if ( ! is_array($data) )
      throw new Exception('An ArrayObject can only be constructed with a data array.');
    
    // Process all the data with the ArrayAccess Notation
    foreach ($data as $key => $value){
      try {
        $this[$key] = $value;
      }
      catch (Exception $e){
        $value = is_array($value) ? idlArray::toString($value, true) : $value;
        throw new Exception(
          "Impossible to parse the key [$key], with the provided data: <<".$value.">>.".
          "Detail of the error: ".$e->getMessage()
        );
      }
    }
  }
  
  /**
   * @see ArrayAccess::offsetExists
   */  
  public function offsetExists($offset) {
    if ( key_exists($offset,$this->getAccess)){
      $value = $this->offsetGet($offset);
      return isset($value);
    }
    return false;
  }

  /**
   * @see ArrayAccess::offsetSet
   */  
  public function offsetSet($offset, $value) {
    if ( ! key_exists($offset,$this->setAccess)){
      throw new Exception("You can't use ArrayObject['$offset'] to change a value...");
    }
    else {
      $method = $this->setAccess[$offset];
      return call_user_func(array($this,$method),$value);
    }
  }

  /**
   * @see ArrayAccess::offsetUnset
   */
  public function offsetUnset($offset) {
    throw new Exception("You can't unset a ArrayObject field");
  }

  /**
   * @see ArrayAccess::offsetGet
   */  
  public function offsetGet($offset) {
    if ( ! key_exists($offset,$this->getAccess)){
      throw new Exception("This value [$offset] doesn't exist with ArrayObject");
    }
    else {
      $params = $this->getAccess[$offset];
      $method = array_shift($params);
      return call_user_func_array(array($this,$method),$params);
    }
  }
  
  /**
   * Used, when data are unusful
   */
  protected function doNothing($data){
  }
  
}