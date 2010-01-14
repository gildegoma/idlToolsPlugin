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
   
}