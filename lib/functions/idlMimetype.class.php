<?php

class idlMimetype extends idlFunction {
  
  private static $mimetypes = null;
  
  public static function getRefFilePath() {
    return realpath(dirname(__FILE__).'/../../data/mimetype.yml');
  }
  
  public static function getFromExtention($ext){
    $ext = strToLower($ext);
    $def = idlArray::get(self::getAll(), $ext, "");
    return isset($def['mime']) ? $def['mime'] : 'application/octet-stream';
  }
  
  public static function getAll(){
    if (self::$mimetypes == null){
      $data = sfYaml::load(self::getRefFilePath());
      self::$mimetypes = $data['extension'];
    }
    return self::$mimetypes;
  }
  
  /**
   * Retunn all extension associated to a given mimetype
   * @param string $mimetype
   * @return array
   */
  public static function getExtensions($mimetype){
    $extensions = array();
    foreach (self::getAll() as $ext => $typeDef){
      if ($typeDef['mime'] == $mimetype) {
        $extensions[] = $ext;
      }
    }
    return $extensions;
  }
  
  public static function getDescriptions($mimetype){
    $descriptions = array();
    foreach (self::getAll() as $ext => $typeDef){
      if ($typeDef['mime'] == $mimetype) {
        $descriptions[] = $typeDef['desc'];
      }
    }
    return $descriptions;
  }
  
}