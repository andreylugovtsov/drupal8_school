<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of currency entities.
 *
 * @ingroup hello_world
 */
class CurrencyListBuilder extends ConfigEntityListBuilder {

  /**
   * Builds the header row for the entity listing.
   *
   * @return array
   *   A render array structure of header strings.
   *
   * @see Drupal\Core\Entity\EntityListController::render()
   */
  public function buildHeader() {
    $header['label'] = $this->t('Name');
    $header['code'] = $this->t('Code');
    $header['display_in_block'] = $this->t('Display in block');
    $header['display_on_page'] = $this->t('Display on page');
    return $header + parent::buildHeader();
  }

  /**
   * Builds a row for an entity in the entity listing.
   *
   * @param EntityInterface $entity
   *   The entity for which to build the row.
   *
   * @return array
   *   A render array of the table row for displaying the entity.
   *
   * @see Drupal\Core\Entity\EntityListController::render()
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['code'] = $entity->id();
    $row['display_in_block'] = $entity->display_in_block;
    $row['display_on_page'] = $entity->display_on_page;
    return $row + parent::buildRow($entity);
  }
}
