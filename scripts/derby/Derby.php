<?php

namespace Derby;

/**
 * @file
 * Contains \Derby\Derby.
 *
 * @package Derby
 * @author chr1sp1n-dev <chr1sp1n.dev@gmail.com>
 */

use Composer\Script\Event;

/**
 * Undocumented class.
 *
 * @package Derby
 */
class Derby {

  /**
   * Undocumented function.
   *
   * @param Composer\Script\Event $event
   *   Composer event.
   *
   */
  public static function init(Event $event) {
    exit('here');
  }

  /**
   * Undocumented function.
   *
   * @param Composer\Script\Event $event
   *   Composer event.
   */
  public static function generateTheme(Event $event) {
    require_once $event
      ->getComposer()
      ->getConfig()
      ->get('vendor-dir') . '/autoload.php';

    exit('here');
  }

  /**
   * Undocumented function.
   *
   * @param Composer\Script\Event $event
   *   Composer event.
   */
  public static function generateModule(Event $event) {
    exit('here');
  }

}
