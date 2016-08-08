<?php
/**
 * @file
 * Contains \Drupal\hello_world\Controller\HelloController.
 */

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\hello_world\Service\CurrenciesUpdater;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Drupal\hello_world\Event\HelloEvent;

/**
 * Class HelloController
 * @package Drupal\hello_world\Controller
 */
class HelloController extends ControllerBase {
  /**
   * Some content output.
   */
  public function content() {
    /** @var EventDispatcher $dispatcher */
    $dispatcher = \Drupal::service('event_dispatcher');

    $event = new HelloEvent('Greetings, friend!');
    $dispatcher->dispatch('hello_world.greetings', $event);

    return array(
      '#type' => 'markup',
      '#markup' => $this->t('You should see message above'),
    );
  }
}
