<?php

class idlRequestExplorerActions extends sfActions {

  public function executeRawText(sfWebRequest $request){
    sfConfig::set('sf_web_debug', false);
    $text = "[Query Explorer, part of the plugin idlToolsPlugin] \n=====Request details======:\n\n";
    $text .= "METHOD: ". $request->getMethodName()."\n";
    
    $text .= "\nGET:\n";
    $text .= $this->parametersToString($request->getGetParameters());

    $text .= "\nPOST:\n";
    $text .= $this->parametersToString($request->getPostParameters());
 
    $text .= "\nUPLOADED FILE:\n";
    $text .= $this->parametersToString($request->getFiles());
        
    return $this->renderText($text);
  }
  
  private function parametersToString($params, $prefix=""){
    $text = "";
    if (is_array($params)){ 
	    foreach ($params as $name => $value){
	      if ( ! is_array($value)){
	        $text .= $prefix." - - ".$name .": " . $value . "\n";
	      }
	      else {
	        $text .= $prefix." - - ".$name .":\n " . $this->parametersToString($value,$prefix." - - ");
	      }
	    }
    }
    return $text;
  }
}