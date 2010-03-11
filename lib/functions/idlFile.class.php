<?php

class idlFile extends idlFunction {
	
	/**
	 * Extract extention for a filename
	 * @param string $filename
	 * @return string
	 */
	public static function getExtention($filename){
    preg_match("@.*\.([^\.]*)@", $filename, $match);
    return $match[1];
	}
	
	/**
	 * Try to guest the mimetype of a file by checking is extention
	 * @param string $filename
	 * @return string
	 */
	public static function guestMimeTypeFormFilename($filename){
		
		// Read extention
	  $extension=strToLower(idlFile::getExtention($filename));
    
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
   * @param string $source The source of the copy, can be a local path or an url
   * @param string $dirPath The destination directory
   * @param array $option :
   *   * new_name : Use it to give an alternative name to the new file
   *   * use_curl : Set it to true to use CURL for retrieved remote files
   */
  public static function copy($source, $dirPath, $options = array()) {
  
    // Merge options with the default one
    $options = array_merge(array(
      'new_name' => '',
      'use_curl' => false
    ),$options);
    
    // Check if it's a file or an url
    if ( is_file($source) ){
      $options['use_curl'] = false;
    }
    else if ( strpos($source, "://") ){
      $source = idlUrl::cleanup($source);
    }
    else {
      throw new Exception("Invalid source provided: ".$source);
    }
    
    // Try to open the source
    if ( $options['use_curl'] ) {
      $fileSource = curl_init($source);
    }
    else {
      $fileSource = fopen ($source, "rb");
    }
    if ( ! $fileSource ) {
      throw new Exception("Impossible to open the file : $source");
    }
    
    // Create or edit the destination
    $destFilename = idlArray::get($options, 'new_name', basename($source));
    $destination = $dirPath.DIRECTORY_SEPARATOR.$destFilename;
    $fileDestination = fopen($destination, "wb");
    if ( ! $fileDestination) {
      throw new Exception("Impossible to create or edit the file : $destination");
    }
    
    // Make the copy
    if ( $options['use_curl'] ){
      curl_setopt($fileSource, CURLOPT_TIMEOUT, ini_get('default_socket_timeout'));
      curl_setopt($fileSource, CURLOPT_FILE, $fileDestination);
      curl_exec($fileSource);
      curl_close($fileSource);
    }
    else {
      while (!feof ($fileSource)) {
        $data = fread ($fileSource, 1024);
        fwrite($fileDestination, $data);
      }
      fclose($fileSource);  
    }
    fclose($fileDestination);
    
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