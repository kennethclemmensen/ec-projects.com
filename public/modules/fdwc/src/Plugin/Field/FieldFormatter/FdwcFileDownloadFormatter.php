<?php

namespace Drupal\fdwc\Plugin\Field\FieldFormatter;

use Drupal\file\Plugin\Field\FieldFormatter\FileFormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the fdwc_file_download formatter.
 *
 * @FieldFormatter(
 *  id = "fdwc_file_download",
 *  label = @Translation("File Download"),
 *  field_types = {
 *      "file"
 *  }
 * )
 */
class FdwcFileDownloadFormatter extends FileFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($this->getEntitiesToView($items, $langcode) as $file) {
      $elements[] = [
        '#theme' => 'fdwc_file_download',
        '#url' => '/fdwc/download/' . $file->id(),
        '#fileName' => $file->getFilename(),
      ];
    }
    return $elements;
  }

}
