<?php

class idlUrl extends idlFunction {
  
  public static function cleanup($url){
  
    return str_replace(" ","%20", $url);
  
  }
  
}
  
