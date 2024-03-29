<?php

class idlLogger {
  
  const ERROR   = 0; // Error
  const INFO    = 1; // Important information
  const DEBUG   = 2; // Message that will be availliable only on debug mode
  const DEV     = 3; // Dev message, should not perssite after dev phase finalize

  private static $singleton;
  private $logger;
  private $logPrefix;
  private $consoleOutput = null; 
  
  // Private constructor as it's a singleton
  private function __construct(){
    
    // Try to retrieved the standard logger
    if (class_exists('sfContext') && sfContext::hasInstance()){
      $this->logger = sfContext::getInstance()->getLogger();
    }
    
    // Define a log prefix
    $this->logPrefix = 'IDL'; // TODO Should be configurable from the app.yml
    
    // Define the standard console output, when we are in cli mode
    if (!isset($_SERVER['HTTP_HOST'])){
      // TODO, also log when functionnal test
      $this->consoleOutput = defined('STDOUT') ? STDOUT : fopen('php://stdout', 'w');
    } 
  }
  
  public static function getInstance(){
    if ( ! self::$singleton ){
      self::$singleton = new idlLogger();
    }
    return self::$singleton;
  }
  
  // To fast log dev message, should not perssite after dev phase finalize 
  public static function dev($msg){
    self::getInstance()->log($msg, self::DEV);
  }
  
  // To fast log dev ARRAY, should not perssite after dev phase finalize 
  public static function devA($array, $msgPrefix = ""){
    self::dev($msgPrefix.idlArray::toString($array, true), self::DEV);
  }
  
  // To log message that will be availliable only on debug mode
  public static function debug($msg){
    self::getInstance()->log($msg, self::DEBUG);
  }
  
  // To log an information message
  public static function info($msg){
    self::getInstance()->log($msg, self::INFO);
  }
  
  // To log an imporant error
  public static function error($msg){
    self::getInstance()->log($msg, self::ERROR);
  }
  
  public function log($msg, $type){
    
    // For dev context, log the DEV log to the console
    if ( $type == self::DEV) {
      $this->tryToLogOnConsole($msg);
    }
    
    // If no intenal logger define, just return
    if (!isset($this->logger)) {
      return; 
    }
    
    switch ($type) {
    case self::DEV:
      if ( sfConfig::get('sf_environment', 'prod') !='dev' ) {
        return; 
      }
      $sfLoggerPriority = sfLogger::DEBUG;
      break;
    case self::DEBUG:
      $sfLoggerPriority = sfLogger::DEBUG;
      break;
    case self::INFO:
      $sfLoggerPriority = sfLogger::INFO;
      break;
    case self::ERROR:
      $sfLoggerPriority = sfLogger::ERR;
      break;
    }
    
    $this->logger->log($this->formatMessage($msg), $sfLoggerPriority);
    
  }
  
  protected function formatMessage($msg){
    return $this->logPrefix . ': ' . $msg;
  } 
  
  
  /**
   * Log on console
   */
  public function tryToLogOnConsole($msg) {
    if (isset($this->consoleOutput)){
      fwrite($this->consoleOutput, $this->formatMessage($msg).PHP_EOL);
      flush();
    }
  }
    
}