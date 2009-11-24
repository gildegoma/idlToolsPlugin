<?php

class idlPublishSfTask extends sfPluginBaseTask {
	
  protected function configure() {
    parent::configure();
    $this->aliases          = array('sf');
    $this->namespace        = 'idl';
    $this->name             = 'publish-sf';
    $this->briefDescription = 'Publish sf with symbolic links';
    $this->detailedDescription = <<<EOT
Publish SF assets with symbolics links ( also works on windows )
EOT;
  }
  
  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array()) {
  	
  	$dest = sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR.'sf';
    $org = sfConfig::get('sf_symfony_lib_dir').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.
      'data'.DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.'sf'; 
  	
  	if ( idlServer::isWindows() ) {
  	  idlFile::winSymlink($dest, $org);
    }
    else {
      $filesystem = new sfFilesystem();
      $filesystem->relativeSymlink($org, $dest, true);
    }
  }
   
}
