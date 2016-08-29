<?php

namespace Drupal\hello_world\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\hello_world\Service\Currencies;

/**
 * @Block(
 *   id = "hello_world_currency_block_v2",
 *   admin_label = @Translation("nbrb.by currency block V2")
 * )
 */
class CurrencyBlockV2 extends BlockBase {
  /**
   * @var Currencies
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
    $this->currencies_services = \Drupal::service('hello_world.currencies');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $currencies = $this->currencies_services->getBlockCurrencies();
    return array(
      '#theme' => 'block_currencies',
      '#currencies' => $currencies,
    );
  }
}
