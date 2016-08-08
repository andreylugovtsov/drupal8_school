<?php

namespace Drupal\hello_world\Service;

/**
 * Class CurrenciesUpdater
 * @package Drupal\hello_world\Service
 */
use Drupal\hello_world\Entity\CurrencyRate;

/**
 * Class CurrenciesUpdater
 * @package Drupal\hello_world\Service
 */
class CurrenciesUpdater {
  /**
   *
   */
  const XML_ENDPOINT = 'http://www.nbrb.by/Services/XmlExRates.aspx';
  /**
   * @var array
   */
  protected $currencies = array();

  /**
   * CurrenciesUpdater constructor.
   */
  public function __construct() {
    $cid = 'currencies_updater' . $this->getRequestParams();
    if ($cache = \Drupal::cache()->get($cid)) {
      $this->currencies = $cache->data;
    }
    else {
      $this->pull();
      if (!empty($this->currencies)) {
        \Drupal::cache()->set($cid, $this->currencies);
      }
    }
  }

  /**
   * @return bool|string
   */
  protected function getRequestParams() {
    return http_build_query([
      'ondate' => date('m/d'),
    ]);
  }

  /**
   *
   */
  protected function pull() {
    $xml_endpoint = self::XML_ENDPOINT . '?' . $this->getRequestParams();

    $xml = simplexml_load_file($xml_endpoint);
    if ($xml) {
      foreach ($xml as $item) {
        $code = (string) $item->CharCode;
        $this->currencies[$code] = [
          'name' => (string) $item->Name,
          'rate' => (string) $item->Rate,
        ];
      }
    }
  }

  /**
   * Get current currency rate.
   *
   * @param string $currency_code
   *
   * @return mixed
   */
  protected function getCurrencyRate($currency_code) {
    $ids = \Drupal::entityQuery('currency_rate')
      ->condition('currency_code', $currency_code)
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

  /**
   * @return array
   */
  public function getCurrenciesKeys() {
    $data = [];
    foreach ($this->currencies as $k => $v) {
      $data[$k] = $k . ' [' . $v['name'] . ']';
    }
    return $data;
  }

  /**
   * @return array
   */
  public function getCurrencies() {
    return $this->currencies;
  }

  /**
   * Perform currency rates updates.
   */
  public function updateCurrencyRates() {
    $entity_manager = \Drupal::entityTypeManager()->getStorage('currency_rate');
    foreach ($this->getCurrencies() as $k => $data) {
      $diff = 0;
      if ($old_rate = $this->getCurrencyRate($k)) {
        $diff = $data['rate'] - $old_rate;
      }
      $currency_rate = $entity_manager->create([
        'rate' => $data['rate'],
        'currency_code' => $k,
        'diff' => $diff,
      ]);
      $currency_rate->save();
    }
  }
}