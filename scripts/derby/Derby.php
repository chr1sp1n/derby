<?php

namespace Derby;

/**
 * @file
 * Contains \Derby\Derby.
 *
 * @package Derby
 * @version 1.0.1
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
    '../private' => []
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
   * Module template folder.
   *
   * @var string
   */
  protected static $moduleTemplateFolder = '.module';

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

    echo PHP_EOL;
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


    if($machineName = self::cloneFolderAndFiles($args[0], self::$themeTemplateFolder, $event)){
      echo "[.OK.] Generated theme with machine name: " . $machineName . PHP_EOL;
      echo "[INFO] Change directory to generated theme and execute 'npm install'." . PHP_EOL;
    }else{
      echo "[ERRO] Some errors occurred while generating the theme." . PHP_EOL;
    }

    echo PHP_EOL;
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

    if($machineName = self::cloneFolderAndFiles($args[0], self::$moduleTemplateFolder, $event)){
      echo "[.OK.] Generated module with machine name: " . $machineName . PHP_EOL;
      echo "[INFO] Change directory to generated module and execute 'npm install'." . PHP_EOL;
    }else{
      echo "[ERRO] Some errors occurred while generating the module." . PHP_EOL;
    }

    echo PHP_EOL;
    exit();
  }

  /**
   * Clone recursovely folders and files
   *
   * @param string $name
   * @param string $themeTemplateFolder
   * @param Event $event
   * @author chr1sp1n-dev <chr1sp1n.dev@gmail.com>
   */
  private static function cloneFolderAndFiles(string $name, string $themeTemplateFolder, Event $event){
    $error = false;

    $vendorDirectory = $event->getComposer()->getConfig()->get('vendor-dir');
    require_once  $vendorDirectory . '/autoload.php';

    $drupalFinder = new DrupalFinder();
    $drupalFinder->locateRoot(getcwd());
    $drupalRoot = $drupalFinder->getDrupalRoot();

    $stringConverter = new StringConverter();
    $machineName = $stringConverter->camelCaseToMachineName($name);
    $machineName = $stringConverter->createMachineName($machineName);

    // echo $drupalRoot . '/../' . self::$themeDevelopmentFolder . '/' . $machineName . \PHP_EOL;
    // exit();
    $newPath = $drupalRoot . '/../' . self::$themeDevelopmentFolder . '/' . $machineName;
    if( file_exists($newPath) ){
      echo "[ERRO] Folder with specified name already exists. Path: " . $newPath . PHP_EOL;
      return FALSE;
    }

    $directory = new \RecursiveDirectoryIterator(self::$themeDevelopmentFolder . '/' . $themeTemplateFolder);
    $iterator = new \RecursiveIteratorIterator($directory);

    foreach ($iterator as $filePath) {
      if( $filePath->isDir() ){
        $path = str_replace( $themeTemplateFolder, $machineName, $filePath->getPathname() );
        $path = $drupalRoot . '/../' . $path;
        if( !file_exists($path) ){
          if(mkdir( $path, 0775, true ) !== FALSE){
            echo "[INFO] Created folder: " . $path . PHP_EOL;
          }else{
            echo "[ERRO] An error occurred while creating the folder: " . $path . PHP_EOL;
            $error = true;
          }
        }
      }
    }

    foreach ($iterator as $filePath) {
      if( !$filePath->isDir() ){
        $file = $filePath->getPathname();
        $data = file_get_contents($file);
        $data = str_replace(self::$replaceString, $machineName, $data);
        $file = str_replace( $themeTemplateFolder, $machineName, $file);
        $fileTheme = $drupalRoot . '/../' . str_replace(self::$replaceString, $machineName, $file);
        if(!file_exists($fileTheme)){
          if(file_put_contents($fileTheme, $data) !== FALSE){
            echo "[INFO] Created file: " . $fileTheme . PHP_EOL;
          }else{
            echo "[ERRO] An error occurred while creating the file: " . $fileTheme . PHP_EOL;
            $error = true;
          }
        }
      }
    }

    return $error ? FALSE : $machineName;
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

    echo $derby;
  }

}

