<?php

namespace Drupal\fdwc\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the fdwc download counter field type.
 *
 * @FieldType(
 *   id = "fdwc_file_download_counter",
 *   label = @Translation("Download counter"),
 *   description = @Translation("Count the number of downloads."),
 *   default_widget = "number",
 *   default_formatter = "fdwc_file_download_counter_formatter"
 * )
 */
class FdwcFileDownloadCounter extends FieldItemBase {

  /**
   * Defines field item properties.
   *
   * @param \Drupal\Core\Field\FieldStorageDefinitionInterface $field_definition
   *   The field definition.
   *
   * @return array
   *   An array of property definitions.
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('integer')
      ->setLabel('Download counter');
    return $properties;
  }

  /**
   * Check if the field is empty.
   *
   * @return bool
   *   true if the field is empty. False otherwise.
   */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    return $value === NULL || $value === '';
  }

  /**
   * Returns the schema for the field.
   *
   * @param \Drupal\Core\Field\FieldStorageDefinitionInterface $field_definition
   *   The field definition.
   *
   * @return array
   *   An associative array with the schema columns.
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type' => 'int',
          'length' => 11,
          'not null' => FALSE,
        ],
      ],
    ];
  }

}
