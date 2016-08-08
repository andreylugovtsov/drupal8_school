<?php

namespace Drupal\hello_world\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\hello_world\Service\CurrenciesUpdater;

/**
 * @Block(
 *   id = "hello_world_currency_block",
 *   admin_label = @Translation("nbrb.by currency block")
 * )
 */
class CurrencyBlock extends BlockBase {
  /**
   * @var CurrenciesUpdater
   */
  protected $currencies_services;

  /**
   * CurrencyBlock constructor.
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currencies_services = \Drupal::service('hello_world.currencies_updater');
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return array(
      'currencies_keys' => array('USD' => 'USD'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['currencies'] = [
      '#title' => $this->t('Currencies'),
      '#type' => 'checkboxes',
      '#options' => $this->currencies_services->getCurrenciesKeys(),
      '#multiple' => TRUE,
      '#description' => $this->t('Select currencies you would like to see.'),
      '#default_value' => $this->configuration['currencies_keys'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['currencies_keys'] = $form_state->getValue('currencies');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $currencies = $this->currencies_services->getCurrencies();
    $currencies_keys = array();
    foreach ($this->configuration['currencies_keys'] as $k => $v) {
      if (!empty($v)) {
        $currencies_keys[$k] = $k;
      }
    }
    $currencies = array_intersect_key($currencies, $currencies_keys);

    return array(
      '#theme' => 'block_currencies',
      '#currencies' => $currencies,
    );
  }
}
