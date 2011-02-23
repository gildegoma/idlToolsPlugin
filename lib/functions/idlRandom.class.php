<?php

class idlRandom extends idlFunction {
  
  /**
   * Generate an unique identifier
   * @return string unique id
   */
  public static function generateUniqueID() {
    $id = "";
    for ($i = 0; ($i < 8); $i++) {
      $id .= sprintf("%02x", mt_rand(0, 255));
    }
    return $id;
  }
  
  /**
   * Retun a human readable string compose of alphanumerics value 
   * @param unknown_type $lenght
   */
  public static function getHumanString($length = 20){
    $val = '';
    $values = 'abcdefghijklmnopqrstuvwxyz0123456789';
    for ( $i = 0; $i < $length; $i++ ) {
      $val .= $values[rand( 0, 35 )];
    }
    return $val;
  }
  
}
