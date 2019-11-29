<?php

namespace Drupal\drupal_extractor\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Configure settings for this module.
 */
class ConfigurationForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'drupal_extractor.settings';
  const FORM_DOM_ID = 'drupal_extractorsettings';


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return str_replace('_', '-', static::FORM_DOM_ID);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);


    $form[static::FORM_DOM_ID]['#attached']['library'][] = 'drupal_extractor/drupal_extractor';

    return parent::buildForm($form, $form_state);

  }



  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable(static::SETTINGS)
      ->save();
    parent::submitForm($form, $form_state);
  }

}
