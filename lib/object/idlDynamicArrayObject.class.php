<?php

/**
 * Extend the idlDynamicObject to provide an advanced constructor that 
 *  accept an associatve array to construct the object
 *  
 * Auto construct an object with an associative array, each key must have been define
 *  with the getConstructionKeyMap. This method must retun an associative array with the 
 *  origin key and the target key. Ex:
 *    array(
 *      'parent_id' => 'parent',
 *      'AlfrescoID' => 'alf_id'
 *    )
 *  The origin key can be every format, but the target key must be a valid underscored string
 *   to be compatible with the idlDynamicObject specification
 *  
 *  By default the value is just copy in the class, but if you want to transform or validate or do
 *   any special traitement, you can do it by creating a method called __constructXXX where 
 *   XXX is the target key name in camel case.
 *  Inside this method, you are free to use the idlDynamicObjet primitive or just to return the 
 *   value to insert in the property. If your return null or nothing, the key will no be create
 *  
 * @author David Jeanmonod - Idael Sàrl - http://www.idael.ch
 */
abstract class idlDynamicArrayObject extends idlDynamicObject {
  
  abstract protected function getConstructionKeyMap();
  
  /**
   * @param array $data must be an associative array
   */
  public function __construct($data = array()){
    
    $exceptionMessagePrefix = "Construction of ".get_class($this)." fail:"; 
    
    // Valid the input array
    if ( ! is_array($data) )
      throw new Exception($exceptionMessagePrefix."Can only be constructed with an associative array.");
      
    // Process all the data with the construction map
    $map = $this->getConstructionKeyMap();  
    foreach ($data as $originKey => $value){
      
      // Validation of the key and the set method name
      if (is_numeric($originKey) || $originKey == ""){
        throw new Exception($exceptionMessagePrefix."Invalid key $originKey found, please use alphanumeric key only");
      }
      if ( ! isset($map[$originKey]) ) {
        throw new Exception($exceptionMessagePrefix."Unkown origin key ".$originKey);
      }
      
      // Try to set the value
      $targetKey = $map[$originKey];
      try {
        $specificConstructor = '__construct'.sfInflector::camelize($targetKey);
        if (method_exists($this, $specificConstructor)){
          $returnValue = $this->$specificConstructor($value);
          if ($returnValue != null){
            $this->addNewProperty($targetKey, $returnValue);
          }
        }
        else {
          $this->addNewProperty($targetKey, $value);
        }
      }
      catch (Exception $e){
        $value = is_array($value) ? idlArray::toString($value, true) : $value;
        throw new Exception(
          "Impossible to set the key [$targetKey], with the provided data: <<".$value.">>.".
          "Detail of the error: ".$e->getMessage()
        );
      }
    }
  }  
}
