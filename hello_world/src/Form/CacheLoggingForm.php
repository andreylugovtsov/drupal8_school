<?php

namespace Drupal\hello_world\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\hello_world\Service\CustomLogger;

/**
 * Class CacheLoggingForm
 * @package Drupal\hello_world\Form
 */
class CacheLoggingForm extends FormBase {
  public function __construct() {
    if (\Drupal::request()->isMethod('GET')) {
      $this->initMessage();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#title'] = $this->t('Logging & cache');

    $form['message'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Type a message'),
      '#maxlength' => 255,
      '#required' => FALSE,
    );

    $form['actions'] = array('#type' => 'actions');

    $form['actions']['save'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save message in log & cache'),
      '#button_type' => 'primary',
      '#name' => 'save',
    );

    $form['actions']['invalidate'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Invaldiate cache'),
      '#button_type' => 'primary',
      '#name' => 'invalidate',
    );

    $form['actions']['delete'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Delete cache'),
      '#button_type' => 'primary',
      '#name' => 'delete',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $message = $form_state->getValue('message');
    $triggering_element = $form_state->getTriggeringElement();
    switch ($triggering_element['#name']) {
      case 'save':
        $this->getCaching()->set($this->getCacheId(), $message);
        $this->getLogger()->logLesson8($message);
        break;
      case 'invalidate':
        $this->getCaching()->invalidate($this->getCacheId());
        drupal_set_message($this->t('Cache invalidated successfully.'));
        break;
      case 'delete':
        $this->getCaching()->delete($this->getCacheId());
        drupal_set_message($this->t('Cache deleted successfully.'));
        break;
    }
  }

  /**
   * @return string
   */
  public function getFormId() {
    return 'cache_and_logging_form';
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $triggering_element = $form_state->getTriggeringElement();
    switch ($triggering_element['#name']) {
      case 'save':
        if (empty($form_state->getValue('message'))) {
          $form_state->setErrorByName('message', $this->t('Can not be empty.'));
        }
        break;
    }
  }

  /**
   * @return \Drupal\Core\Cache\CacheBackendInterface
   */
  protected function getCaching() {
    return \Drupal::cache('lesson8');
  }

  /**
   * @return CustomLogger
   */
  protected function getLogger() {
    return \Drupal::service('logger.lesson8');
  }

  /**
   * @return string
   */
  protected function getCacheId() {
    return 'lesson8-message';
  }

  /**
   *
   */
  protected function initMessage() {
    $cache_object = $this->getCaching()->get($this->getCacheId(), TRUE);
    if ($cache_object) {
      if ($cache_object->valid) {
        drupal_set_message($this->t('Valid cache item: %s.', ['%s' => $cache_object->data]));
      }
      else {
        drupal_set_message($this->t('Invalid cache item: %s.', ['%s' => $cache_object->data]), 'error');
      }
    }
    else {
      drupal_set_message($this->t('There are no items in cache.'), 'warning');
    }
  }
}