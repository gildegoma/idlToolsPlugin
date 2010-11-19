<?php

class idlJson extends idlFunction {

  /**
   * Decode a json string
   * @param string $json
   * @return array
   */
  public static function decode($json) {

    // Decode and return array
    $array = json_decode($json, true);
    if ( isset($array) ){
      return $array;
    }
    
    // Error management
    $errorMessages = array(
      JSON_ERROR_NONE           => 'No error...',
      JSON_ERROR_DEPTH          => 'Maximum stack depth exceeded',
      JSON_ERROR_CTRL_CHAR      => 'Unexpected control character found, probably wrong encoding',
      JSON_ERROR_SYNTAX         => 'Syntax error, malformed JSON',
      JSON_ERROR_STATE_MISMATCH => 'JSON invalid or bad formated',
//      JSON_ERROR_UTF8           => 'Bad caracter formating, probably wrong encoding'  // Only since php 5.3.3
    );
    $error = $errorMessages[json_last_error()]; 
    throw new Exception("idlJson:decode() error: $error\n This happend when parsing\n$json");        
  
  }
  
  /**
   * Encode an array to json string
   * @param array $array
   * @return string
   */
  public static function encode($array) {
    
    return json_encode($array);
  
  }
  
}