<?php

class idlUrl extends idlFunction {
  
  public static function cleanup($url){
    $cleanUrl = "";
    $parts = parse_url($url);
    $cleanUrl .= $parts['scheme'].'://'.$parts['host'];
    if (isset($parts['path'])){
      $path = substr($parts['path'],1);
      $pathParts = explode('/', $path);
      foreach ($pathParts as $pathPart){
        $cleanUrl .= '/' . rawurlencode($pathPart);
      }  
    }
    
    if (isset($parts['query'])){
      $cleanUrl .= '?' . rawurlencode($parts['query']);
    }
    if (isset($parts['fragment'])){
      $cleanUrl .= '#' . rawurlencode($parts['fragment']);
    }
    return $cleanUrl;
  }
}
  
