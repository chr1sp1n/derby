<?php

namespace Drupal\drupal_extractor\Utility;


/**
 * Language utility
 *
 * @author chr1sp1n-dev <chr1sp1n.dev@gmail.com>
 */
class Language {

  public static function current(){
    $languageCode = \Drupal::languageManager()->getCurrentLanguage()->getId();
    return $languageCode;
  }

}
