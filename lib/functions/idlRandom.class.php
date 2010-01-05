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
  
}
