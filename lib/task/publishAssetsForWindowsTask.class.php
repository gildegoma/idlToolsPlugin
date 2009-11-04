<?php

class publishAssetsForWindowsTask extends sfBaseTask {
	
  protected function configure() {
    $this->aliases          = array('pa');
    $this->namespace        = 'idl';
    $this->name             = 'publish-assets';
    $this->briefDescription = 'Publish assets working with symbolic links on windows based sytem';
    $this->detailedDescription = 
      'Publish assets with symbolics links over the windows based sytem, compatible with XP, Vista and Seven ';
  }

  protected function execute($arguments = array(), $options = array()) {
    $plugins = $this->configuration->getPlugins();
    foreach ($this->configuration->getAllPluginPaths() as $pluginName => $pluginPath)    {
      if (!in_array($pluginName, $plugins) || ($options['core-only'] && dirname($pluginPath) != $this->configuration->getSymfonyLibDir().'/plugins')){
        continue;
      }      
      $this->log("New symlink for $pluginName");
      $this->symlink($webDir, sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR.$pluginName);
    }
  }
  
  // Return true if we are on windows vista
  private function is_vista() {
    return  (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') && (php_uname('r') == "6.0" );
  }
  
  // Return true if we are on windows vista
  private function is_seven() {
    return (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') && (php_uname('r') == "6.1" );
  }

  // Return true if we are on windows xp
  private function is_xp() {
    return (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') && (php_uname('r') == "5.1");
  }

  // Allow to create symbolic links on Vista
  private function symlink ($target, $link) {
	  if ($this->is_vista() || $this->is_xp() || $this->is_seven()) {
	    // Change / to \ because it will break otherwise.
	    $target = str_replace('/', '\\', $target);
	    $link = str_replace('/', '\\', $link);
	  }
	  if ($this->is_vista() || $this->is_seven()  ) {
	  	$command = 'mklink ' . '/j' . ' "' . $link . '" "' . $target . '"';
	    //$this->log($command);
	    return exec($command);
	  } else
	  if ($this->is_xp()) {
	    $command = sfConfig::get('sf_plugins_dir')."\idlToolsPlugin\bin\junction.exe $link $target";
	    //$this->log($command);
      return exec($command);
	  } else {
	    throw new Exception("This function on working only on Windows based system");
	  }
	}
  
}
