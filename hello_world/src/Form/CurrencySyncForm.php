<?php

namespace Drupal\hello_world\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\hello_world\Service\CurrenciesUpdater;

/**
 * Class CurrencySyncForm
 * @package Drupal\hello_world\Form
 */
class CurrencySyncForm extends ConfirmFormBase {
  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Sync');
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var CurrenciesUpdater $currencies_service */
    $currencies_service = \Drupal::service('hello_world.currencies_updater');
    $currencies_service->updateCurrencyRates();
    drupal_set_message(t('Currency rates updated successfully.'));
    $form_state->setRedirectUrl($this->getCancelUrl());
  }

  /**
   * @return string
   */
  public function getFormId() {
    return 'currency_rate_sync_form';
  }

  /**
   * @return \Drupal\Core\Url
   */
  public function getCancelUrl() {
    return new Url('entity.currency_rate.list');
  }

  /**
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   */
  public function getQuestion() {
    return $this->t('Sync currency rates?');
  }
}