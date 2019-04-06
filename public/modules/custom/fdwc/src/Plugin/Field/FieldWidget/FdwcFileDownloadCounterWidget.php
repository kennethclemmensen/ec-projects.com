<?php
namespace Drupal\fdwc\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\WidgetInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the File Download Counter widget
 *
 * @FieldWidget(
 *   id = "fdwc_file_download_counter_widget",
 *   label = @Translation("Download counter widget"),
 *   field_types = {
 *     "fdwc_file_download_counter"
 *   }
 * )
 */
class FdwcFileDownloadCounterWidget extends WidgetBase implements WidgetInterface {

    /**
     * {@inheritdoc}
     */
    public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) : array {
        $element += [
            '#type' => 'textfield',
            '#default_value' => (isset($items[$delta]->value)) ? $items[$delta]->value : '',
            '#size' => 7,
            '#maxlength' => 7
        ];
        return ['value' => $element];
    }
}