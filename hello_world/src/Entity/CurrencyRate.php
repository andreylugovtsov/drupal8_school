<?php

namespace Drupal\hello_world\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\hello_world\CurrencyRateInterface;
use Drupal\Core\Entity\EntityChangedTrait;

/**
 * Defines the CurrencyRate entity.
 *
 * @ContentEntityType(
 *   id = "currency_rate",
 *   label = @Translation("Currency Rate entity"),
 *   admin_permission = "access content",
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\hello_world\Entity\Controller\CurrencyRateListBuilder",
 *     "form" = {
 *       "delete" = "Drupal\hello_world\Form\CurrencyRateDeleteForm"
 *     }
 *   },
 *   base_table = "currency_rate",
 *   links = {
 *     "delete-form" = "/currency_rate/{currency_rate}/delete",
 *     "collection" = "/currency_rates_entities",
 *     "canonical" = "/currency_rate/{currency_rate}",
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "currency_code",
 *     "uuid" = "uuid"
 *   },
 * )
 */
class CurrencyRate extends ContentEntityBase implements CurrencyRateInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the CurrencyRate entity.'))
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the CurrencyRate entity.'))
      ->setReadOnly(TRUE);

    $fields['currency_code'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Currency code'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 3,
      ));

    $fields['rate'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Rate'));

    $fields['diff'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Diff'));

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    return $fields;
  }

  /**
   * @return mixed
   */
  public function getRate() {
    return $this->rate->value;
  }
}
