<?php

class idlFile {
	
	/**
	 * 
	 * @param unknown_type $filename
	 * @return unknown_type
	 */
	public static function guestMimeTypeFormFilename($filename){
		
		// Read extention
	  preg_match("@.*\.([^\.]*)@", $filename, $match);
    $extension=strToLower($match[1]);
    
    // Compare to list
    // TODO cache the list for multiple call
    $extentionList=sfYaml::load(sfConfig::get('sf_plugins_dir').DIRECTORY_SEPARATOR.
      'idlToolsPlugin'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'mimetype.yml');
    $extentionList = $extentionList['extension'];
    if(array_key_exists($extension, $extentionList)){ 
      return $extentionList[$extension]['mime'];
    }
    else {
    	// Default extension
      return 'application/octet-stream';
    }
	}
	
	/**
	 * Copy a file or an url to a local file
	 * @param unknown_type $url
	 * @param unknown_type $dirname
	 * @param unknown_type $new_name
	 * @return boolean copy success
	 */
	public static function copyFile($source, $dirname, $new_name=""){
    @$file = fopen ($source, "rb");
    if ($file) {
      $filename = basename($source);
      $fc = fopen($dirname.DIRECTORY_SEPARATOR.($new_name != "" ? $new_name : $filename), "wb");
      while (!feof ($file)) {
        $line = fread ($file, 1028);
        fwrite($fc,$line);
      }
      fclose($fc);
    }
    else {
      throw new Exception("Impossible to open the file : $source");
    }
  }
}