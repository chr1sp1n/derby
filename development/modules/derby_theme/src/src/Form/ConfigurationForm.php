<?php

namespace Drupal\derby_theme\Form;

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
  const SETTINGS = 'derby_theme.settings';
  const FORM_DOM_ID = 'derby_themesettings';


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


    $form[static::FORM_DOM_ID]['#attached']['library'][] = 'derby_theme/derby_theme';

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
