<?php

namespace Drupal\hello_world;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a CurrencyRate entity.
 *
 * We have this interface so we can join the other interfaces it extends.
 */
interface CurrencyRateInterface extends ContentEntityInterface, EntityChangedInterface {

}
