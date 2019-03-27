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
     *
     *
     * @param FieldItemListInterface $items
     * @param string $langcode
     * @return array
     */
    public function viewElements(FieldItemListInterface $items, $langcode) : array {
        $elements = [];
        foreach($this->getEntitiesToView($items, $langcode) as $item) {
            $elements[] = [
                '#theme' => 'fdwc_file_download_counter',
                '#counter' => $item->value,
            ];
        }
        return $elements;
    }
}