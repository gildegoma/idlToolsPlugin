<?php

class sfWebDebugPanelAssets extends sfWebDebugPanel
{
  public function getTitle()
  {
    return 'Assets list';
  }

  public function getPanelTitle()
  {
    return 'Stylesheet and JavaScript files from sfWebResponse';
  }

  public function getPanelContent()
  {
    $response = sfContext::getInstance()->getResponse();
    sfLoader::loadHelpers('Asset');
    $html = '';

    if ($stylesheets = $response->getStylesheets())
    {
      $html .= '<h2>Stylesheets</h2><ul>';
      foreach ($stylesheets as $file => $options)
      {
        $html .= sprintf('<li><strong>%s</strong> %s</li>', stylesheet_path($file), count($options) ? '('.var_export($options, true).')' : '');;
      }
      $html .= '</ul>';
    }

    if ($javascripts = $response->getJavascripts())
    {
      $html .= '<h2>Javascripts</h2><ul>';
      foreach ($javascripts as $file => $options)
      {
        $html .= sprintf('<li><strong>%s</strong> %s</li>', javascript_path($file), count($options) ? '('.var_export($options, true).')' : '');;
      }
      $html .= '</ul>';
    }

    return $html;
  }
}