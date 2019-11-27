<?php

namespace Drupal\__theme_name__\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Configure example settings for this site.
 */
class ConfigurationForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = '__theme_name__.settings';
  const FORM_DOM_ID = '__theme_name__settings';


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

    $chats = $form_state->get('telegram_chats_list');
    if ($chats === null){
      $chats = $config->get('telegram_chats_list');
      if(!$chats) $chats = [];
    }
    $form_state->set('telegram_chats_list', $chats);

    //$form['#tree'] = true;
    $form[static::FORM_DOM_ID] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Telegram settings'),
      '#prefix' => '<div id="' . static::FORM_DOM_ID . '">',
      '#suffix' => '</div>',
    );

    $form[static::FORM_DOM_ID]['telegram_bot_api_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Bot API - URL'),
      '#default_value' => $config->get('telegram_bot_api_url'),
    ];

    $form[static::FORM_DOM_ID]['telegram_bot_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Bot token'),
      '#default_value' => $config->get('telegram_bot_token'),
    ];

    $form[static::FORM_DOM_ID]['chats'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Chats'),
    );

    $form[static::FORM_DOM_ID]['chats']['telegram_default_chat_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Default chat'),
      '#options' => $chats,
      '#description' => 'Destination chat. Use <i>Remove selected chat</i> button to remove chat from list.',
    ];

    $form[static::FORM_DOM_ID]['chats']['remove_chat'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Remove selected chat'),
      '#submit' => ['::removeChat'],
      '#ajax' => [
        'callback' => '::ajaxChatCallback',
        'wrapper' => static::FORM_DOM_ID,
        'progress' => [
          'type' => 'throbber',
          'message' => null,
        ],
      ],
    );

    $form[static::FORM_DOM_ID]['chats']['telegram_new_chat'] = [
      '#type' => 'textfield',
      '#title' => $this->t('New chat'),
      '#description' => 'Pipe separated chat_id|chat name. (-1234567|TestChannel)',
      '#default_value' => null,
      '#prefix' => '<br><hr><br>'
    ];

    $form[static::FORM_DOM_ID]['chats']['add_chat'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Add chat'),
      '#submit' => ['::addChat'],
      '#ajax' => [
        'callback' => '::ajaxChatCallback',
        'wrapper' => static::FORM_DOM_ID,
        'progress' => [
          'type' => 'throbber',
          'message' => null,
        ],
      ],
    );

    $form[static::FORM_DOM_ID]['#attached']['library'][] = 'telegram_bot/telegram_settings';

    return parent::buildForm($form, $form_state);

  }

  /**
   */
  public function ajaxChatCallback(array &$form, FormStateInterface $form_state) {
    $form[static::FORM_DOM_ID]['chats']['telegram_new_chat']['#value'] = '';
    return $form[static::FORM_DOM_ID];
  }

  /**
   */
  public function addChat(array &$form, FormStateInterface $form_state) {
    $chats = $form_state->get('telegram_chats_list');
    $newChat = $form_state->getValue('telegram_new_chat');
    if($newChat){
      $newChat = explode('|', $newChat);
      if(!$chats) $chats = [];
      if(count($newChat)==2) $chats[$newChat[0]] = $newChat[1] . ' (' . $newChat[0] . ')';
      if(count($newChat)==1) $chats[$newChat[0]] = $newChat[0];
    }
    $form_state->set('telegram_chats_list', $chats);
    $form_state->setRebuild();
  }

  /**
   */
  public function removeChat(array &$form, FormStateInterface $form_state) {
    $chats = $form_state->get('telegram_chats_list');
    $chatToRemove = $form_state->getValue('telegram_default_chat_id');
    if(isset($chats[$chatToRemove])) unset($chats[$chatToRemove]);
    $form_state->set('telegram_chats_list', $chats);
    $form_state->setRebuild();
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('telegram_bot_api_url', $form_state->getValue('telegram_bot_api_url'))
      ->set('telegram_bot_token', $form_state->getValue('telegram_bot_token'))
      ->set('telegram_default_chat_id', $form_state->getValue('telegram_default_chat_id'))
      ->set('telegram_chats_list', $form_state->get('telegram_chats_list'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
