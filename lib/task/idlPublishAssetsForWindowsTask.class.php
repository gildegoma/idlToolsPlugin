<?php

class idlPublishAssetsForWindowsTask extends sfPluginPublishAssetsTask {
	
  protected function configure() {
    parent::configure();
    $this->aliases          = array('win-symlink');
    $this->namespace        = 'idl';
    $this->name             = 'publish-assets';
    $this->briefDescription = 'Publish assets with symbolic links on Windows based sytem';
    $this->detailedDescription = <<<EOT
Publish assets with symbolics links over the windows based sytem, compatible with XP, Vista and Seven.
  For Vista and Seven it used the integrated mklink function
  For XP, it use the executable "junction.exe"
EOT;
  }

  /**
   * (non-PHPdoc)
   * @see lib/vendor/symfony/lib/task/plugin/sfPluginPublishAssetsTask#installPluginAssets($plugin, $dir)
   */
  protected function installPluginAssets($plugin, $dir) {
    $webDir = $dir.DIRECTORY_SEPARATOR.'web';
    if (is_dir($webDir)) {
      idlFile::winSymlink($webDir, sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR.$plugin);
    }
  }
   
}
