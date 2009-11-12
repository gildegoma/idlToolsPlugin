<?php
class idlFunction {
   
  public function __construct(){
    throw new Exception("This is class contains only static method and cannot be instanciated");
  }
  
}