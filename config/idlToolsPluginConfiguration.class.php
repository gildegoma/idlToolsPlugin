<?php


class idlToolsPluginConfiguration extends sfPluginConfiguration {
  
  public function initialize() {
    $this->dispatcher->connect('debug.web.load_panels', array($this, 'registerPanel'));
  }
  
  /**
   * Register the DebugPanel to the debug bar
   * @param sfEvent $event
   */
  public static function registerPanel(sfEvent $event) {
    $webDebugToolbar = $event->getSubject();
    $newPanel = new sfWebDebugPanelAssets($webDebugToolbar);
    $webDebugToolbar->setPanel($newPanel->getTitle(),$newPanel );
  }
}