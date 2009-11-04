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
  
  
  public static function sendToClient($filepath, $filename=""){
    
    // Guessing filename
    if ($filename=="") {
      $filename = basename($filepath);
    }
    
    // Analize of the file and log
    $path = preg_replace('@[\\\\/]@', DIRECTORY_SEPARATOR, $filepath);
      preg_match("@.*\.([^\.]*)@", $filename, $match);
    $extension=strToLower($match[1]);
    $type = self::guestMimeTypeFormFilename($filename);
    sfContext::getInstance()->getLogger()->info("Start download of the file: $path, name: $filename, extension: $extension, mimetype: $type");
        
    // Detection du browser
    if(preg_match("@Opera(/| )([0-9].[0-9]{1,2})@", $_SERVER['HTTP_USER_AGENT'], $resultats))
      $browser="Opera";
    elseif(preg_match("@MSIE ([0-9].[0-9]{1,2})@", $_SERVER['HTTP_USER_AGENT'], $resultats))
      $browser="Internet Explorer";
    else 
      $browser="Mozilla";
    
    // date courante
    $now=gmdate('D, d M Y H:i:s').' GMT';
    
    // Configuration des headers
    header('Last-Modified', $now);
    header('Expires', $now); 
    header("Content-Description: File Transfer");
    header("Content-Type: $type");
    // TODO, convert the filename, because if it contains space, it's not transmitted complettly
    header("Content-Disposition: attachment; filename=$filename");
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($path));
    
    // Internet Explorer specific header
    if(preg_match('/msie|(microsoft internet explorer)/i', $_SERVER['HTTP_USER_AGENT'])) {
      header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
      header('Pragma', 'public');
    }
    else{
      header('Pragma', 'no-cache');
    }
    
    // Envoi du fichier
    ob_end_clean();
    readfile($path);
    
  }
}