<?php

class idlString extends idlFunction {
  
	/**
	 * Camelize a text with underscore
	 * @param string $text 
	 * @return string
	 */
  public static function camelize($text){
    $type = self::guestStringType($text);
    if ($type == 'invalid'){
      throw new Exception("Impossible to convert string [$text] to camelize style");
    } 
    else if ($type == 'notype'){
      return ucfirst($text);
    }
    else if ($type == 'css') {
      $words = explode('-',$text);
    }
    else if ($type == 'underscore') {
      $words = explode('_',$text);
    }
    else {
      // Already a css style string
      return $text;
    }
    $camelString = "";
    foreach ($words as $word){
      $camelString .= ucfirst($word);
    }
    return $camelString;
  }
  
  /**
   * Underscorify a string to from underscord or camelize string to css-selector style
   * @param string $text 
   * @return string
   */
  public static function underscorify($text){
    $type = self::guestStringType($text);
    if ($type == 'invalid'){
      throw new Exception("Impossible to convert string [$text] to unerscored style");
    } 
    else if ($type == 'notype'){
      return $text;
    }
    else if ($type == 'css') {
      return str_replace('-', '_', $text);
    }
    else if ($type == 'camel') {
      $text = ucfirst($text); 
      preg_match_all('/([A-Z][a-z]*)|(\d+)/',$text,$match);
      return strtolower(implode('_',$match[0]));
    }
    else {
      // Already a css style string
      return $text;
    }
  }
    
  
  /**
   * cssify
   * @param string $text 
   * @return string
   */
  public static function cssify($text){
    $type = self::guestStringType($text);
    if ($type == 'invalid'){
      throw new Exception("Impossible to convert string [$text] to css style");
    } 
    else if ($type == 'untyped'){
      return $text;
    }
    else if ($type == 'underscore') {
      return str_replace('_', '-', $text);
    }
    else if ($type == 'camel') {
      $text = ucfirst($text); 
      preg_match_all('/([A-Z][a-z]*)|(\d+)/',$text,$match);
      return strtolower(implode('-',$match[0]));
    }
    else {
      // Already a css style string
      return $text;
    }
  }
  
  /**
   * Try to detect the type of a string. Throw an exception if the test is wrong
   * @return string      Can be css, camel, underscore or untyped
   */
  public static function guestStringType($text){
    // First raw validation
    if (preg_match("/[^a-z0-9_\-]/i",$text)){
      throw new Exception("Invalid caracter found in the string identifier: ".$text);
    }
    // Guessing type
    if (preg_match("/^[a-z][a-z]*$/",$text)) {
      return 'untyped';
    }
    else if (preg_match("/^[a-zA-Z][a-zA-Z0-9]*$/",$text)) {
      return 'camel';
    }
    else if (preg_match("/^[a-z]([a-z])*(\-([a-z]+|\d+))*$/",$text)) {
      return 'css';
    }
    else if (preg_match("/^[a-z]([a-z])*(\_([a-z]+|\d+))*$/",$text)) {
      return 'underscore';
    }
    else {
      return 'invalid';
    }
  }
  
  
  
  /**
   * Convert camelized string in css style string
   * Example: "MyClassName" is converted to "my-class-name"
   * Only letters and numbers are allowed all other chars will raise en exception
   * @param string $text The string to convert 
   * @deprecated  Must use the generic method cssify
   * @return string The string converted in css style
   */
  public static function convertCamelizedToCssStyle($text){
    return self::cssify($text);
  }   
  
   
}