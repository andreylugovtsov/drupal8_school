<?php

namespace Drupal\hello_world\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class HelloEvent
 * @package Drupal\hello_world\Event
 */
class HelloEvent extends Event {
  /**
   * @var $message string
   */
  protected $message;

  /**
   * HelloEvent constructor.
   * @param $message
   */
  public function __construct($message) {
    $this->message = $message;
  }

  /**
   * @return string
   */
  public function getMessage() {
    return $this->message;
  }
}
