<?php

namespace Drupal\hello_world\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\file\Entity\File;
use Drupal\hello_world\Entity\Currency;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CurrencyFormBase.
 *
 * Typically, we need to build the same form for both adding a new entity,
 * and editing an existing entity. Instead of duplicating our form code,
 * we create a base class. Drupal never routes to this class directly,
 * but instead through the child classes of CurrencyAddForm and CurrencyEditForm.
 *
 * @package Drupal\hello_world\Form
 *
 * @ingroup hello_world
 */
class CurrencyFormBase extends EntityForm {

  /**
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $entityQueryFactory;

  /**
   * Construct the CurrencyFormBase.
   *
   * For simple entity forms, there's no need for a constructor. Our currency form
   * base, however, requires an entity query factory to be injected into it
   * from the container. We later use this query factory to build an entity
   * query for the exists() method.
   *
   * @param \Drupal\Core\Entity\Query\QueryFactory $query_factory
   *   An entity query factory for the currency entity type.
   */
  public function __construct(QueryFactory $query_factory) {
    $this->entityQueryFactory = $query_factory;
  }

  /**
   * Factory method for CurrencyFormBase.
   *
   * When Drupal builds this class it does not call the constructor directly.
   * Instead, it relies on this method to build the new object. Why? The class
   * constructor may take multiple arguments that are unknown to Drupal. The
   * create() method always takes one parameter -- the container. The purpose
   * of the create() method is twofold: It provides a standard way for Drupal
   * to construct the object, meanwhile it provides you a place to get needed
   * constructor parameters from the container.
   *
   * In this case, we ask the container for an entity query factory. We then
   * pass the factory to our class as a constructor parameter.
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('entity.query'));
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::form().
   *
   * Builds the entity add/edit form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   An associative array containing the current state of the form.
   *
   * @return array
   *   An associative array containing the currency add/edit form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Get anything we need from the base class.
    $form = parent::buildForm($form, $form_state);

    /** @var Currency $currency */
    $currency = $this->entity;

    // Build the form.
    $form['id'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Code'),
      '#default_value' => $currency->id(),
      '#disabled' => !$currency->isNew(),
      '#required' => TRUE,
    );
    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $currency->label(),
      '#required' => TRUE,
    );
    $form['display_in_block'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Display in block'),
      '#default_value' => $currency->display_in_block,
    );
    $form['display_on_page'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Display on page'),
      '#default_value' => $currency->display_on_page,
    );
    $form['currency_image'] = array(
      '#type' => 'managed_file',
      '#title' => $this->t('Currency image'),
      '#upload_location' => 'public://images/',
      '#required' => FALSE,
      '#upload_validators' => array(
        'file_validate_extensions' => array('gif png jpg jpeg'),
        'file_validate_size' => array(1000000),
      ),
    );

    // Return the form.
    return $form;
  }

  /**
   * Checks for an existing currency.
   *
   * @param string $currency_code
   *   Currency code.
   *
   * @return bool
   *   TRUE if this format already exists, FALSE otherwise.
   */
  public function exists($currency_code) {
    // Use the query factory to build a new currency entity query.
    $query = $this->entityQueryFactory->get('currency');

    // Query the entity ID to see if its in use.
    $query->condition('id', $currency_code);
    if (!$this->entity->isNew()) {
      $query->condition('id', $this->entity->id(), '<>');
    }

    $result = $query->execute();

    // We don't need to return the ID, only if it exists or not.
    return (bool) $result;
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::actions().
   *
   * To set the submit button text, we need to override actions().
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
    // Get the basic actins from the base class.
    $actions = parent::actions($form, $form_state);

    // Change the submit button text.
    $actions['submit']['#value'] = $this->t('Save');

    // Return the result.
    return $actions;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!preg_match('/^[A-Z]{3,3}$/', $form_state->getValue('id'))) {
      $form_state->setErrorByName('id', $this->t('Currency code can only contain 3 uppercase letters.'));
    }
    if ($this->exists($form_state->getValue('id'))) {
      $form_state->setErrorByName('id', $this->t('Currency code already in use.'));
    }
    parent::validateForm($form, $form_state);
  }


  /**
   * Overrides Drupal\Core\Entity\EntityFormController::save().
   *
   * Saves the entity. This is called after submit() has built the entity from
   * the form values. Do not override submit() as save() is the preferred
   * method for entity form controllers.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   An associative array containing the current state of the form.
   */
  public function save(array $form, FormStateInterface $form_state) {
    // EntityForm provides us with the entity we're working on.
    $currency = $this->getEntity();

    // Drupal already populated the form values in the entity object. Each
    // form field was saved as a public variable in the entity class. PHP
    // allows Drupal to do this even if the method is not defined ahead of
    // time.
    $status = $currency->save();

    // Grab the URL of the new entity. We'll use it in the message.
    $url = $currency->urlInfo();

    // Create an edit link.
    $edit_link = Link::fromTextAndUrl($this->t('Edit'), $url)->toString();

    if ($status == SAVED_UPDATED) {
      // If we edited an existing entity...
      drupal_set_message($this->t('Currency %label has been updated.', array('%label' => $currency->label())));
      $this->logger('contact')->notice('Currency %label has been updated.', ['%label' => $currency->label(), 'link' => $edit_link]);
    }
    else {
      // If we created a new entity...
      drupal_set_message($this->t('Currency %label has been added.', array('%label' => $currency->label())));
      $this->logger('contact')->notice('Currency %label has been added.', ['%label' => $currency->label(), 'link' => $edit_link]);
    }

    // Redirect the user back to the listing route after the save operation.
    $form_state->setRedirect('entity.currency.list');
  }
}
