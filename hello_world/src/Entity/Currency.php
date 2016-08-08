<?php

namespace Drupal\hello_world\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the currency entity.
 *
 * @ingroup hello_world
 *
 * @ConfigEntityType(
 *   id = "currency",
 *   label = @Translation("Currency"),
 *   admin_permission = "access content",
 *   handlers = {
 *     "list_builder" = "Drupal\hello_world\Controller\CurrencyListBuilder",
 *     "form" = {
 *       "add" = "Drupal\hello_world\Form\CurrencyAddForm",
 *       "edit" = "Drupal\hello_world\Form\CurrencyEditForm",
 *       "delete" = "Drupal\hello_world\Form\CurrencyDeleteForm"
 *     }
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name"
 *
 *   },
 *   links = {
 *     "edit-form" = "/currency/manage/{currency}",
 *     "delete-form" = "/currency_rate/{currency_rate}/delete"
 *   }
 * )
 */
class Currency extends ConfigEntityBase {

  /**
   * Currency ID.
   *
   * @var string
   */
  public $id;

  /**
   * Currency name.
   *
   * @var string
   */
  public $name;

  /**
   * Currency display in block flag.
   *
   * @var boolean
   */
  public $display_in_block;

  /**
   * Currency display on page flag.
   *
   * @var boolean
   */
  public $display_on_page;

  /**
   * @return CurrencyRate
   */
  public function getRate() {
    $ids = \Drupal::entityQuery('currency_rate')
      ->condition('currency_code', $this->id())
      ->sort('created', 'DESC')
      ->range(0, 1)
      ->execute();
    if ($ids) {
      $currency_rate = \Drupal::entityTypeManager()
        ->getStorage('currency_rate')
        ->load(reset($ids));
      if ($currency_rate) {
        return $currency_rate->getRate();
      }
    }
  }
}
