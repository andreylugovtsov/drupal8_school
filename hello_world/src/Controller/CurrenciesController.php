<?php
/**
 * @file
 * Contains \Drupal\hello_world\Controller\HelloController.
 */

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\hello_world\Service\Currencies;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

/**
 * Class CurrenciesController
 * @package Drupal\hello_world\Controller
 */
class CurrenciesController extends ControllerBase {
  /**
   * List all currencies.
   */
  public function listAll() {
    /** @var Currencies $currencies_service */
    $currencies_service = \Drupal::service('hello_world.currencies');
    $currencies = $currencies_service->getPageCurrencies();

    return array(
      'currencies list' => [
        '#theme' => 'block_currencies',
        '#currencies' => $currencies,
      ],
      'current time' => [
        '#type' => 'element_current_time',
        '#label' => $this->t('Current time'),
        '#format' => DATE_RFC822,
      ],
    );
  }

  /**
   * Redirect response example.
   */
  public function listAllAlias() {
    $url = Url::fromRoute('hello_world.currencies_list');
    $response = new RedirectResponse($url->getInternalPath());
    drupal_set_message($this->t('Redirected from alias'));
    return $response;
  }

  /**
   * Json response example.
   */
  public function json($limit = NULL) {
    /** @var CurrenciesUpdater $currencies_service */
    $currencies_service = \Drupal::service('hello_world.currencies_updater');
    $currencies = $currencies_service->getCurrencies();
    if ($limit) {
      $currencies = array_slice($currencies, 0, $limit);
    }
    $response = new JsonResponse($currencies);
    return $response;
  }

  /**
   * Sync currency rates.
   */
  public function sync() {
    /** @var CurrenciesUpdater $currencies_service */
    $currencies_service = \Drupal::service('hello_world.currencies_updater');
    $currencies = $currencies_service->getCurrencies();
    $response = new JsonResponse($currencies);
    return $response;
  }
}
