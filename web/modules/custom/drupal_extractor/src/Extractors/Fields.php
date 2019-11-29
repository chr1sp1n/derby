<?php

namespace Drupal\drupal_extractor\Extractors;


class Fields {

  public static function file($field){
    if( $values = self::getRawValues($field) ){

    }
    return NULL;
  }

  public static function image($field){
    return NULL;
  }

  public static function link($field){
    return NULL;
  }

  public static function integer($field){
    if( $rawValues = self::getRawValues($field) ){
      $values = [];
      foreach ($rawValues as $key => $value) {
        if(isset($value['value'])) $values[] = (int) $value['value'];
      }
    }
    return empty($values) ? NULL : $values;
  }

  public static function string($field){
    if( $rawValues = self::getRawValues($field) ){
      $values = [];
      foreach ($rawValues as $key => $value) {
        if(isset($value['value'])) $values[] = $value['value'];
      }
    }
    return empty($values) ? NULL : $values;
  }

  public static function stringLong($field){
    return self::string($field);
  }

  public static function boolean($field){
    return [FALSE];
  }

  public static function textLong($field){
    return self::string($field);
  }

  public static function textWithSummary($field){
    return [NULL];
  }

  public static function paragraph($field){
    return NULL;
  }

  public static function entityReference($field){
    return [NULL];
  }

  /**
   * Undocumented function
   *
   * @param [type] $field
   * @author chr1sp1n-dev <chr1sp1n.dev@gmail.com>
   */
  private static function getRawValues($field){
    if(!$field instanceof \Drupal\Core\Field\FieldItemList ){
      return null;
    }
    $values = $field->getValue();
    return !is_null($values) && is_array($values) ? $values : NULL;
  }


}
