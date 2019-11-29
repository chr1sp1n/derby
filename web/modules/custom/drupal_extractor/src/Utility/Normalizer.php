<?php

namespace Drupal\drupal_extractor\Utility;

class Normalizer {

  public static function className(string $string){
    return self::toUpperCamelCase($string);
  }

  public static function methodName(string $string){
    return self::toLowerCamelCase($string);
  }

  private static function toUpperCamelCase(string $string){
    $class = str_replace(['_','-','\\','/'], [' ',' ',' ',' '], $string);
    $class = ucwords($class);
    $class = str_replace(' ', '', $class);
    return $class;
  }

  private static function toLowerCamelCase(string $string){
    $class = str_replace(['_','-','\\','/'], [' ',' ',' ',' '], $string);
    $class = ucwords($class);
    $class = str_replace(' ', '', $class);
    $class = lcfirst($class);
    return $class;
  }

}
