<?php

namespace Drupal\drupal_extractor\Extractors\Entities;

use Drupal\drupal_extractor\Utility;
use Drupal\drupal_extractor\Extractors\Fields;

/**
 * Node fields extractor
 *
 * @method void get(&$variables)
 *
 * @author chr1sp1n-dev <chr1sp1n.dev@gmail.com>
 */
class Node {


  /**
   * Extract all translated fields in current node except the fields in $fieldExcluded array
   *
   * @param string $language_code
   * @param array $fieldsExcluded
   *
   * @author chr1sp1n-dev <chr1sp1n.dev@gmail.com>
   */
  public static function get(&$variables, string $languageCode = null, array $fieldsExcluded = []){

    $node = \Drupal::routeMatch()->getParameter('node');
    if (!$node instanceof \Drupal\node\NodeInterface) {
      return;
    }

    $languageCode = Utility\Language::current();

    $node = self::getTranslated($node, $languageCode);

    $data = new \stdClass();
    $data->language_code = $languageCode;
    $data->node_url = $node->toUrl('canonical', [ 'language' => $node->language(), 'absolute' => true ])->toString();


    if($fields = $node->getFields()){
      foreach ($fields as $fieldName => $field) {
        if(in_array($fieldName, $fieldsExcluded)) continue;

        //if( strpos($fieldName,'field_') !== false ){
          $typeName = $field->getFieldDefinition()->getType();
          $method = Utility\Normalizer::methodName($typeName);
          if( method_exists( '\Drupal\drupal_extractor\Extractors\Fields', $method ) ){
            $data->{$fieldName} = Fields::$method($field, $languageCode);
          }
        //}

      }

    }


    $variables['fields'] = $data;

  }

  /**
   * Get translated node.
   *
   * @param [type] $node
   * @param string $language_code
   * @author chr1sp1n-dev <chr1sp1n.dev@gmail.com>
   */
  public static function getTranslated($node, string $language_code = null){
    if (!$node instanceof \Drupal\node\NodeInterface) {
      return null;
    }
    $language_code = $language_code ? $language_code : Utility\Language::current();
    if($node->hasTranslation($language_code)) {
      return $node->getTranslation($language_code);
    }
    return $node;
  }

  /**
   * Get node canonical url.
   *
   * @param [type] $node
   * @author chr1sp1n-dev <chr1sp1n.dev@gmail.com>
   */
  public static function getUrl($node){
    if (!$node instanceof \Drupal\node\NodeInterface) {
      return null;
    }
    return $node->toUrl('canonical', [ 'language' => $node->language(), 'absolute' => true ])->toString();;
  }

}
