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

use Composer\Script\Event;
use DrupalFinder\DrupalFinder;
use Drupal\Component\Utility\Crypt;
use Symfony\Component\Filesystem\Filesystem;
use Drupal\Console\Core\Utils\StringConverter;


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
   * String to replace.
   *
   * @var string
   *
   */
  protected static $replaceString = '__theme_name__';

  /**
   * Theme development folder,
   *
   * @var string
   */
  protected static $themeDevelopmentFolder = 'development/themes';

  /**
   * Theme template folder.
   *
   * @var string
   */
  protected static $themeTemplateFolder = '.template';

  /**
   * Derby initialization script.
   *
   * @param Composer\Script\Event $event
   *   Composer event.
   *
   * @author chr1sp1n-dev <chr1sp1n.dev@gmail.com>
   */
  public static function init(Event $event) {
    self::showBanner();
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
    self::showBanner();
    $args = $event->getArguments();
    if(empty($args)){
      echo "[ERRO] Parameter theme name needed." . PHP_EOL;
      exit();
    }

    $vendorDirectory = $event->getComposer()->getConfig()->get('vendor-dir');
    require_once  $vendorDirectory . '/autoload.php';

    $drupalFinder = new DrupalFinder();
    $drupalFinder->locateRoot(getcwd());
    $drupalRoot = $drupalFinder->getDrupalRoot();

    $stringConverter = new StringConverter();
    $themeName = $stringConverter->createMachineName($args[0]);

    $directory = new \RecursiveDirectoryIterator(self::$themeDevelopmentFolder . '/' . self::$themeTemplateFolder);
    $iterator = new \RecursiveIteratorIterator($directory);

    foreach ($iterator as $filePath) {
      if( $filePath->isDir() ){
        $path = str_replace( self::$themeTemplateFolder, $themeName, $filePath->getPathname() );
        $path = $drupalRoot . '/../' . $path;
        if( !file_exists($path) ){
          mkdir( $path, 0775, true );
        }
      }
    }

    foreach ($iterator as $filePath) {
      if( !$filePath->isDir() ){
        $file = $filePath->getPathname();
        $data = file_get_contents($file);
        $data = str_replace(self::$replaceString, $themeName, $data);
        $file = str_replace( self::$themeTemplateFolder, $themeName, $file);
        $fileTheme = $drupalRoot . '/../' . str_replace(self::$replaceString, $themeName, $file);
        if(!file_exists($fileTheme)){
          file_put_contents($fileTheme, $data);
        }
      }
    }


    exit();
  }

  /**
   * Derby generate module script.
   *
   * @param Composer\Script\Event $event
   *   Composer event.
   */
  public static function generateModule(Event $event) {
    self::showBanner();
    $args = $event->getArguments();
    if(empty($args)){
      echo "[ERRO] Parameter theme name needed." . PHP_EOL;
      exit();
    }

    $vendorDirectory = $event->getComposer()->getConfig()->get('vendor-dir');
    require_once  $vendorDirectory . '/autoload.php';

    $drupalFinder = new DrupalFinder();
    $drupalFinder->locateRoot(getcwd());
    $drupalRoot = $drupalFinder->getDrupalRoot();

    $stringConverter = new StringConverter();
    $moduleName = $stringConverter->createMachineName($args[0]);

    exit();
  }

  private static function showBanner(){
    $derby = <<<DERBY
              888                888
          e88 888  ,e e,  888,8, 888 88e  Y8b Y888P
         d888 888 d88 88b 888  " 888 888b  Y8b Y8P
         Y888 888 888   , 888    888 888P   Y8b Y
          "88 888  "YeeP" 888    888 88"     888
                                             888
                          hibo © - v1.0.1    888
DERBY;

    echo PHP_EOL . $derby . PHP_EOL . PHP_EOL;
  }

}

