<?php

namespace Drupal\hello_world\Service;

/**
 * Class CustomLogger
 */
/**
 * Class CustomLogger
 * @package Drupal\hello_world\Service
 */
class CustomLogger {
  /**
   * @var \Drupal\Core\Logger\LoggerChannelFactory
   */
  private $logger_factory;

  /**
   * CustomLogger constructor.
   * @param $logger
   */
  public function __construct($logger) {
    $this->logger_factory = $logger;
  }

  /**
   * @param $message
   */
  public function logLesson8($message) {
    $this->logger_factory->get('lesson8')->warning($message);
    $this->logger_factory->get('system')->emergency($message);
  }
}
