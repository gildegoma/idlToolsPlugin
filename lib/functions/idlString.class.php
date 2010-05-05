<?php

class idlString extends idlFunction {
  
	/**
	 * Camelize a text with underscore
	 * @param string $text 
	 * @return string
	 */
  public static function camelize($text){
    return sfToolkit::pregtr($text, array('#/(.?)#e' => "'::'.strtoupper('\\1')", '/(^|_|-)+(.)/e' => "strtoupper('\\2')"));
  }
  
  /**
   * Convert camelized string in css style string
   * Example: "MyClassName" is converted to "my-class-name"
   * Only letters and numbers are allowed all other chars will raise en exception
   * @param string $text The string to convert 
   * @return string The string converted in css style
   */
  public static function convertCamelizedToCssStyle($text){
    
    if (preg_match("/[^a-z0-9]/i",$text)){
      throw new Exception("Only letters or numbers are allowed in camelized name");
    }
    //convert the string to an array
    $text = str_split($text);
    
    //lowcase the first char
    $text[0]=strtolower($text[0]);
    $ret = "";
    foreach ($text as $k=>$c){
      if (ctype_upper($c)) $c = "-".strtolower($c);
      $ret .= $c;
    }
    return $ret;
  } 
   
}