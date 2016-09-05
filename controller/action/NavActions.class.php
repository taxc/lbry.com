<?php

class NavActions extends Actions
{
  protected static $navUri;

  public static function setNavUri($uri)
  {
    static::$navUri = $uri;
  }

  public static function getNavUri()
  {
    return static::$navUri ?: Request::getRelativeUri();
  }

  public static function prepareFooterPartial(array $vars)
  {
    return $vars + [
      'isDark' => false,
      'showLearnFooter' => false
    ];
  }

  public static function prepareGlobalItemsPartial(array $vars)
  {
    $vars += ['selectedItem' => static::getNavUri()];
    return $vars;
  }

  public static function prepareLearnFooterPartial(array $vars)
  {
    return $vars + [
      'isDark' => true
    ];
  }
  
  public static function execute404()
  {
    $uri = Request::getRelativeUri();
    Controller::queueToRunAfterResponse(function() use($uri) {
      Slack::sendErrorIfProd('404 for url ' . $uri);
    });
    Response::setStatus(404);
    return ['page/404'];
  }
}