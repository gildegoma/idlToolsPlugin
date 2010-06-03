<?php 

/**
 * Dynamic object allow to :
 *  
 *  * Dynamically create new properties through addNewProperty(name, value)
 *  * Dynamically remove  properties through removeProperty(name)
 *  * Enable / disable acces to properties through enableXXX and disableXXX
 *  * Check if a property exist and is enable through hasXXX() method
 *  * Access properties trough standard getter and setter getXXX and setXXX method
 *  * Sub class can override getter and setter and access property with getProperty($name) and setProperty(name, value) 
 * 
 * @author David Jeanmonod - Idael Sàrl - http://www.idael.ch
 *
 */
class idlDynamicObject {
  
  private $properties = array();
  private $disabledProperties = array();
  
  /**
   * Dynamically create new property
   */
  public function addNewProperty($name, $value){
    if (isset($this->properties[$name])){
      throw new Exception("Try to add an already existing property $name");
    }
    $this->validPropertyName($name);
    $this->properties[$name] = $value;
  }
  
  private function validPropertyName($name){
    // Regex on the prop name, refuse uppercase and specal car execpt _
  }
  
  private function checkPropertyExistance($name){
    if ( ! isset($this->properties[$name])){
      throw new Exception("Property $name does not exist");
    }
  }
  
  public function removeProperty($name){
    $this->checkPropertyExistance($name);
    unset($this->properties[$name], $this->disabledProperties[$name]);
  }

  public function disableProperty($name){
    $this->checkPropertyExistance($name);
    $this->disabledProperties[$name] = true;
  }
  
  public function enableProperty($name){
    $this->checkPropertyExistance($name);
    unset($this->disabledProperties[$name]);
  }
  
  protected function getProperty($name){
    $this->checkPropertyExistance($name);
    return $this->properties[$name];
  }
  
  protected function setProperty($name, $value){
    $this->checkPropertyExistance($name);
    $this->properties[$name] = $value;
  }
  
  /**
   * Magic method to catch all setXXX getXXX and hasXXX
   */
  public function __call($method, $arguments) {
    
    // We must catch only setter,getter and hasXXX method
    if (in_array($verb = substr($method, 0, 3), array('set', 'get', 'has')) && strlen($method) > 3) {
      
      // Validate the arguments number
      if (in_array($verb, array('get', 'has')) && count($arguments) > 0){
        throw new Exception("Method $method doesn't support arguments");
      }
      elseif ($verb == 'set' && count($arguments) != 1){
        throw new Exception("Method $method need 1 argument as the value to set");
      }
      
      // Call the based function
      $name = sfInflector::underscore(substr($method, 3));
      if ($verb == 'set'){
        $this->setProperty($name, $arguments[0]);
      }
      elseif ($verb == 'get'){
        return $this->getProperty($name);
      }
      else { 
        // hasXXX
        return isset($this->properties[$name]) && ! isset($this->disabledProperties[$name]);
      }   
    }
  }
}
   

