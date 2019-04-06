<?php
namespace Drupal\fdwc\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\TypedData\Exception\MissingDataException;

/**
 * Plugin implementation of the Download Counter field
 *
 * @FieldType(
 *   id = "fdwc_file_download_counter",
 *   label = @Translation("Download counter"),
 *   module = "fdwc",
 *   description = @Translation("Count the number of downloads."),
 *   default_formatter = "fdwc_file_download_counter_formatter",
 *   default_widget = "fdwc_file_download_counter_widget"
 * )
 */
class FdwcFileDownloadCounter extends FieldItemBase implements FieldItemInterface {

    /**
     * {@inheritdoc}
     */
    public static function propertyDefinitions(FieldStorageDefinitionInterface $fieldDefinition) : array {
        $properties['value'] = DataDefinition::create('integer')->setLabel('Download counter');
        return $properties;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty() : bool {
        try {
            $value = $this->get('value')->getValue();
            return $value === null || $value === '';
        } catch(MissingDataException $ex) {
            return true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function schema(FieldStorageDefinitionInterface $fieldDefinition) : array {
        return [
            'columns' => [
                'value' => [
                    'type' => 'int',
                    'length' => 11,
                    'not null' => false
                ]
            ]
        ];
    }
}