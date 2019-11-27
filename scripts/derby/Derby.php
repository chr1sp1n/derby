<?php

namespace Derby;

/**
 * @file
 * Contains \Derby\Derby.
 *
 * @package Derby
 * @version 1.0.0
 * @author chr1sp1n-dev <chr1sp1n.dev@gmail.com>
 */

use Chr1sp1n\Console;
use Composer\Script\Event;
use DrupalFinder\DrupalFinder;
use Drupal\Component\Utility\Crypt;
use Symfony\Component\Filesystem\Filesystem;


/**
 * Undocumented class.
 *
 * @package Derby
 */
class Derby {

  /**
   * Folders and files to be created after Drupal installation.
   *
   * @var array
   *
   */
  protected static $folders = [
    '../.tmp' => [],
    '../private' => ['.gitkeep']
  ];

  /**
   * Theme files to be renamed or edit
   *
   * @var array
   *
   */
  protected static $themeFiles = [
    '__theme_name__.info.yml'       => NULL,
    '__theme_name__.libraries.yml'  => NULL,
    '__theme_name__.theme'          => NULL,
    'drussets.config.json'          => '__theme_name__'
  ];

  /**
   * Derby initialization script.
   *
   * @param Composer\Script\Event $event
   *   Composer event.
   *
   * @author chr1sp1n-dev <chr1sp1n.dev@gmail.com>
   */
  public static function init(Event $event) {
    $vendorDirectory = $event->getComposer()->getConfig()->get('vendor-dir');
    require_once  $vendorDirectory . '/autoload.php';

    $drupalFinder = new DrupalFinder();
    $drupalFinder->locateRoot(getcwd());
    $drupalRoot = $drupalFinder->getDrupalRoot();

    $fs = new Filesystem();
    foreach (self::$folders as $folder => $files) {
      if (!$fs->exists($drupalRoot . '/'. $folder)) {
        echo "[INFO] Generate folder: ". $drupalRoot . '/'. $folder . \PHP_EOL;
        $fs->mkdir($drupalRoot . '/'. $folder);
        foreach ($files as $file) {
          echo "[INFO] Generate file: ". $drupalRoot . '/'. $folder . '/' . $file . \PHP_EOL;
          $fs->touch($drupalRoot . '/'. $folder . '/' . $file);
        }
      }
    }

    echo "[INFO] Sets Drupal temporary folder." . \PHP_EOL;
    echo exec($vendorDirectory . '/bin/drush config-set system.file path.temporary ../tmp -y');

    if (!$fs->exists($drupalRoot . '/sites/default/salt.txt')) {
      echo "[INFO] Generates salt.txt file." . \PHP_EOL;
      $salt = Crypt::randomBytesBase64(55);
      if(!empty($salt)){
        file_put_contents($drupalRoot . '/sites/default/salt.txt', $salt);
      }
    }

    exit();
  }

  /**
   * Derby generate theme script.
   *
   * @param Composer\Script\Event $event
   *   Composer event.
   */
  public static function generateTheme(Event $event) {
    $args = $event->getArguments();
    if(empty($args)){
      echo "[ERRO] Parameter theme name needed.";
      exit();
    }



    $fs = new Filesystem();
    exit();
  }

  /**
   * Derby generate module script.
   *
   * @param Composer\Script\Event $event
   *   Composer event.
   */
  public static function generateModule(Event $event) {
    $fs = new Filesystem();
    exit();
  }

  private static function showBanner(){

  }
}

