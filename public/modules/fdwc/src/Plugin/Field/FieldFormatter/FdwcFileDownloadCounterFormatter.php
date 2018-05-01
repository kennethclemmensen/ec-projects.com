<?php

namespace Drupal\fdwc\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the fdwc file download counter formatter.
 *
 * @FieldFormatter (
 *   id = "fdwc_file_download_counter_formatter",
 *   label = @Translation("Download counter"),
 *   field_types = {
 *     "fdwc_file_download_counter"
 *   }
 * )
 */
class FdwcFileDownloadCounterFormatter extends FormatterBase {

  /**
   * Builds a renderable array for a field value.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $items
   *   The field values to be rendered.
   * @param string $langcode
   *   The language that should be used to render the field.
   *
   * @return array
   *   A renderable array for $items, as an array of child elements keyed by
   *   consecutive numeric indexes starting from 0.
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $item) {
      $elements[] = [
        '#theme' => 'fdwc_file_download_counter',
        '#counter' => $item->value,
      ];
    }
    return $elements;
  }

}
