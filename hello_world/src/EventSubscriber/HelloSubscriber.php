<?php

namespace Drupal\hello_world\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\hello_world\Event\HelloEvent;

/**
 * Class HelloSubscriber
 * @package Drupal\hello_world\EventSubscriber
 */
class HelloSubscriber implements EventSubscriberInterface {
  /**
   * @return mixed
   */
  public static function getSubscribedEvents() {
    $events['hello_world.greetings'][] = array('onGreetings', 0);
    return $events;
  }

  /**
   * @param \Drupal\hello_world\Event\HelloEvent $event
   */
  public function onGreetings(HelloEvent $event) {
    drupal_set_message($event->getMessage());
  }
}