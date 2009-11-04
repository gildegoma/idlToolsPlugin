<?php

/**
 * Convieniant methods to help dealing with Ajax query
 *
 */
abstract class sfAjaxActions extends sfActions {
	
  /**
   * Convert the php array in JSON and appends to the response content and bypasses the built-in view system.
   * 
   * This method must be called as with a return:
   * <code>return $this->renderJson(array)</code>
   * 
   * If the request is call with a direct http get, not an XmlHttpRequest, the method will throw an error, excepted
   * if we are in dev mode, in this case the request will me publish in a debug page.
   *
   * @param array $data The data array that need to be convert to json
   *
   */
  public function renderJson($data) {
  	
    // We return JSON only in XmlHttpRequest
    if ($this->getRequest()->isXmlHttpRequest()){
    	$json = json_encode($data);
      return $this->renderText($json);
    }
    // Else we throw an Exception or display the debug page 
    else {
    	if ( ! sfContext::getInstance()->getConfiguration()->isDebug() ){
    		$this->forward404("This page is accessible only through an XmlHttpRequest");
    	} else {
    		// Remove the escape strategy in order to display the table with print_r
    		sfConfig::set('sf_escaping_strategy',false);
    		$this->data = $data;
    		$this->webRequest = $this->getRequest();
    		$this->setTemplate(sfConfig::get('sf_app_template_dir')."/jsonDebug");
    	}  	
    }
  }
}
?>