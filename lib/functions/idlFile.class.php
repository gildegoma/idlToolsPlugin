<?php

class idlFile extends idlFunction {
	
	/**
	 * Try to guest the mimetype of a file by checking is extention
	 * @param string $filename
	 * @return string
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
  
    // If not a file, guest it's an url, so try to clean up
    if ( ! is_file($source) ){
      $source = idlUrl::cleanup($source);
    }
    
    // Proceed the copy
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
    // Unable to open, throw an exception
    else {
      throw new Exception("Impossible to open the file : $source");
    }
  }
  
  /**
   * Send the specified file to the client
   * @param string $path Local path to file
   * @param string $filename Name of the attatchement, by default it use the current
   *        filename
   */
  public static function sendFileToClient($path, $filename = null, $mimetype = ""){
    return self::__sendToClient(array(
      'type' => 'file',
      'path' => preg_replace('@[\\\\/]@', DIRECTORY_SEPARATOR, $path),
      'filename' => isset($filename) ? $filename : basename($path),
      'size' => filesize($path),
      'mimetype' => $mimetype
    ));
  }

  /**
   * Send the specified data stream to the client
   * @param string $data The data stream to send
   * @param string $filename Name of the attatchement
   */
  public static function sendDataToClient($data, $filename, $mimetype = ""){
    return self::__sendToClient(array(
      'type' => 'data',
      'data' => $data,
      'filename' => $filename,
      'size' => strlen($data),
      'mimetype' => $mimetype
    ));
  }
  
  /**
   * Send the specified URL to the client
   * @param string $url URL to retrieved and to send
   * @param string $filename Name of the attatchement
   * @param int $size Size of the remote content in bytes 
   */
  public static function sendUrlToClient($url, $filename, $size = 0, $mimetype = ""){
    return self::__sendToClient(array(
      'type' => 'url',
      'path' => $url,
      'filename' => $filename,
      'size' => $size,
      'mimetype' => $mimetype
    ));
    
  }
  
  
  /**
   * Private function send content to client, don't use it directly, use instead 
   *  the functions:
   *   * sendFileToClient
   *   * sendDataToClient
   *   * sendUrlToClient
   * @param array $options Various options of the send
   * 
   * TODO Need a full review of all header to better match standard
   */
  public static function __sendToClient($options){
      
    // Get mimetype if not define
    if ($mimetype == "") {
      $mimetype = self::guestMimeTypeFormFilename($options['filename']);
    }
    
    sfContext::getInstance()->getLogger()->info("Start download of the file: {$options['filename']}, mimetype: $mimetype");
        
    // Browser detection
    if(preg_match("@Opera(/| )([0-9].[0-9]{1,2})@", $_SERVER['HTTP_USER_AGENT'], $resultats))
      $browser="Opera";
    elseif(preg_match("@MSIE ([0-9].[0-9]{1,2})@", $_SERVER['HTTP_USER_AGENT'], $resultats))
      $browser="Internet Explorer";
    else 
      $browser="Mozilla";
    
    // Format the current date
    $now = gmdate('D, d M Y H:i:s').' GMT';
    
    // Write down the headers
    $resp = sfContext::getInstance()->getResponse();
    $resp->setHttpHeader('Last-Modified', $now);
    $resp->setHttpHeader('Expires', $now); 
    $resp->setHttpHeader('Content-Description',"File Transfer");
    $resp->setHttpHeader('Content-Type', $mimetype);
    // TODO, convert the filename, because if it contains space, it's not transmitted complettly
    $resp->setHttpHeader("Content-Disposition", "attachment; filename=".$options['filename']);
    $resp->setHttpHeader('Content-Transfer-Encoding', 'binary');
    $resp->setHttpHeader('Expires', 0);
    $resp->setHttpHeader('Content-Length', $options['size']);
    
    // Internet Explorer specific headers
    if ($browser == "Internet Explorer") {
      $resp->setHttpHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
      $resp->setHttpHeader('Pragma', 'public');
    }
    else{
      $resp->setHttpHeader('Pragma', 'no-cache');
    }
    
    // Sending, the ob_end_clean in mandatory to avoid memory_limit problem
    ob_end_clean();
    // Force sending the header
    $resp->sendHttpHeaders();
    // Transfer the file
    if ( $options['type'] == 'data' ) {
      echo $options['data'];
    }
    else {
      readfile($options['path']);
    }
    
  }
  
  
  /**
   * Create symlink on Windows based system
   * @param unknown_type $target
   * @param unknown_type $link
   * @return unknown_type
   */
  public static function winSymlink ($target, $link) {  
    // Convert potential / to \ 
    $target = str_replace('/', '\\', $target);
    $link = str_replace('/', '\\', $link);
    
    // Vista and Seven have a native function
    if ( in_array(idlServer::getOS(), array("VISTA", "SEVEN"))  ) {
      $command = 'mklink ' . '/j' . ' "' . $link . '" "' . $target . '"';
      //$this->log($command);
      return exec($command);
    } 
    // XP require an executable
    elseif ( idlServer::getOS() == "XP") {
      $command = sfConfig::get('sf_plugins_dir')."\idlToolsPlugin\bin\junction.exe $link $target";
      //$this->log($command);
      return exec($command);
    } 
    // Other system is not appicable
    else {
      throw new Exception("This function is working only on XP, Vista and Seven based system");
    }
  }  
  
  public static function createCleanFileName($dirtyName){
    $dirtyName = utf8_decode($dirtyName);
    $withoutAccent = strtr($dirtyName,utf8_decode('ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ'),
                                                  'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
    //return $withoutAccent;
    $withoutMaj = strtolower($withoutAccent);
    //return $withoutMaj;
    $withoutSpecCar = preg_replace("/[^a-z0-9\\.\\-\\_]/i", "_", $withoutMaj);
    //Allows letters a-z, digits, point (\\.), hyphen (\\-), and underscore (\\_)
    return $withoutSpecCar;
  }
}