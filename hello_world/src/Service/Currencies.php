<?php

namespace Drupal\hello_world\Service;

/**
 * Class Currencies
 * @package Drupal\hello_world\Service
 */
use Drupal\hello_world\Entity\CurrencyRate;

/**
 * Class Currencies
 * @package Drupal\hello_world\Service
 */
class Currencies {
  /**
   * Get currencies list.
   *
   * @return array
   */
  public function getCurrencies() {
    $ids = \Drupal::entityQuery('currency')->execute();
    return \Drupal::entityTypeManager()
      ->getStorage('currency')
      ->loadMultiple($ids);
  }
  /**
   * Get currencies for page.
   *
   * @return array
   */
  public function getPageCurrencies() {
    $ids = \Drupal::entityQuery('currency')
      ->condition('display_on_page', TRUE)
      ->execute();
    return \Drupal::entityTypeManager()
      ->getStorage('currency')
      ->loadMultiple($ids);
  }
  /**
   * Get currencies for page.
   *
   * @return array
   */
  public function getBlockCurrencies() {
    $ids = \Drupal::entityQuery('currency')
      ->condition('display_in_block', TRUE)
      ->execute();
    return \Drupal::entityTypeManager()
      ->getStorage('currency')
      ->loadMultiple($ids);
  }
}