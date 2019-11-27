<?php

/**
 * @file
 * Contains \Derby\composer\ScriptHandler.
 */

namespace Derby;

use Composer\Script\Event;

class ComposerScripts{

  public static function init(Event $event){
    exit('here');
  }

  public static function generateTheme(Event $event){
    require_once $event
      ->getComposer()
      ->getConfig()
      ->get('vendor-dir').'/autoload.php';

    exit('here');
  }

  public static function generateModule(Event $event){
    exit('here');
  }

}
