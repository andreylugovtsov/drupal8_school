<?php

namespace Drupal\hello_world\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Class CurrencyEditForm.
 *
 * Provides the edit form for our Currency entity.
 *
 * @package Drupal\hello_world\Form
 *
 * @ingroup hello_world
 */
class CurrencyEditForm extends CurrencyFormBase {

  /**
   * Returns the actions provided by this form.
   *
   * For the edit form, we only need to change the text of the submit button.
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
    $actions['submit']['#value'] = $this->t('Update Currency');
    return $actions;
  }

}
