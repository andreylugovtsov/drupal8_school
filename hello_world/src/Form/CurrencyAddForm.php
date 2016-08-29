<?php

namespace Drupal\hello_world\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Class CurrencyAddForm.
 *
 * Provides the add form for our Currency entity.
 *
 */
class CurrencyAddForm extends CurrencyFormBase {

  /**
   * Returns the actions provided by this form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   An associative array containing the current state of the form.
   *
   * @return array
   *   An array of supported actions for the current entity form.
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    $actions = parent::actions($form, $form_state);
    $actions['submit']['#value'] = $this->t('Create Currency');
    return $actions;
  }
}
