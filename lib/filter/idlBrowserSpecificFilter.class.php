<?php

class browserFilter extends sfFilter {
  
  public static $managedBrowsers = array(
    'msie' => 'ei',
    'firefox' => 'ff',
    'safari' => 'saf',
    'chrome' => 'cr'
  );
 
  public function execute($filterChain) { 
    
    // Nothing to do before the action
    $filterChain->execute();
    
    // Explore the browser
    $browser = new dmBrowser();
    $browser->configureFromUserAgent($_SERVER['HTTP_USER_AGENT']);
    
    // Create the css classes
    $newClasses = "";
    if (key_exists($browser->getName(), self::$managedBrowsers)) {
      $newClasses = "if-browser-".self::$managedBrowsers[$browser->getName()];
      $mainVersion = substr($browser->getVersion(),0,1);
      $newClasses .= " v".$mainVersion[0];
    }
    
    // Decorate the response with the browser version of the client
    if ($newClasses != "") {
      // Catch the body parameters
      $response = $this->getContext()->getResponse();
      preg_match("@<body([^>]*)>@", $response->getContent(), $match);
      $bodyParameters = $match[1];
      
      // Catch the potential existing class parameter
      $existingClasses = "";
      if (preg_match("@class\s*=\s*[\"']([^\"']*)[\"']@", $bodyParameters, $match) == 1) {
        $classParam = $match[0];
        $existingClasses = $match[1];
        // Write down the new classes
        $bodyParameters = str_replace($classParam, "class='$existingClasses $newClasses'", $bodyParameters);
      }
      else {
        $bodyParameters .= " class='$newClasses'";
      }
              
      $response->setContent(preg_replace("@<body[^>]*>@", "<body $bodyParameters>", $response->getContent()));
    }
  }
}

?>