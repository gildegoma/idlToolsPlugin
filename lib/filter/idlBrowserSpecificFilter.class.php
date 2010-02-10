<?php

/**
 * Symfony filter allowing to add specific browser class in the body tag
 *  this allow to easily add browser specific CSS rules.
 *  
 *  Exemple:
 *   In your CSS file, you can do things like this:
 *   
 *   #toolbar {
 *     color : red;
 *   }
 *   .if-browser-ie #toolbar {
 *     color: blue;
 *   }
 *   .if-browser-ie.v6 #toolbar {
 *     display: none;
 *   }
 *    
 * @author David Jeanmonod - Idael Sàrl - http://www.idael.ch
 *
 */
class idlBrowserSpecificFilter extends sfFilter {
  
  public static $managedBrowsers = array(
    'msie' => 'ie',
    'firefox' => 'ff',
    'safari' => 'saf',
    'chrome' => 'cr'
  );
 
  public function execute($filterChain) { 
    
    // Forward to next filter, action will be done after action process
    $filterChain->execute();
    
    // Init the Diam browser explorer
    $browser = new dmBrowser();
    $userAgent = $this->getContext()->getRequest()->getHttpHeader('User-Agent');
    $browser->configureFromUserAgent($userAgent);
    
    // Create the css classes
    $newClasses = "";
    if (key_exists($browser->getName(), self::$managedBrowsers)) {
      $newClasses = "if-browser-".self::$managedBrowsers[$browser->getName()];
      $mainVersion = substr($browser->getVersion(),0,1);
      $newClasses .= " v".$mainVersion[0];
    }
    
    // If classes have been generated, try to add them to body
    if ($newClasses != "") {
      $response = $this->getContext()->getResponse();
    	// If there is a body tab in the response, add the browser class to it
      if (preg_match("@<body([^>]*)>@", $response->getContent(), $match) ==1){
       $bodyParameters = $match[1];
	      // Catch the potential existing class parameter
 	      if (preg_match("@class\s*=\s*[\"']([^\"']*)[\"']@", $bodyParameters, $match) == 1) {
	        $classParam = $match[0];
	        $existingClasses = $match[1];
	        $bodyParameters = str_replace($classParam, "class='$existingClasses $newClasses'", $bodyParameters);
	      }
	      else {
	        $bodyParameters .= " class='$newClasses'";
	      }
	      $response->setContent(preg_replace("@<body[^>]*>@", "<body $bodyParameters>", $response->getContent()));
	    }
    }
  }
}

?>