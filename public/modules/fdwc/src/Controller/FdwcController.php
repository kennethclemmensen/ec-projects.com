<?php

namespace Drupal\fdwc\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Drupal\file\Entity\File;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * The Controller contains a method to download a file.
 */
class FdwcController extends ControllerBase {

  /**
   * The storage handler class for nodes.
   *
   * @var \Drupal\node\NodeStorage
   */
  private $nodeStorage;

  /**
   * Class constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity
   *   The Entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity) {
    $this->nodeStorage = $entity->getStorage('node');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Download the file specified by the id.
   *
   * @param \Drupal\file\Entity\File $file
   *   The file.
   *
   * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
   *   A HTTP response that downloads the file.
   */
  public function download(File $file) {
    if (!is_null($file)) {
      $this->updateCounterField();
      $response = new BinaryFileResponse($file->getFileUri());
      $response->trustXSendfileTypeHeader();
      $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $file->getFilename());
      return $response;
    }
    else {
      throw new NotFoundHttpException();
    }
  }

  /**
   * Update the counter field.
   */
  private function updateCounterField() {
    // TODO: Get the current node id.
    $node_id = 450;
    $node = $this->nodeStorage->load($node_id);
    // TODO: Should check the node exist before check the field.
    // TODO: The name is not always field_download_counter.
    $field_name = 'field_download_counter';
    if ($node->hasField($field_name)) {
      $field = $node->get($field_name);
      $new_value = $field->getValue()[0]['value'] + 1;
      $field->setValue($new_value);
      $node->save();
    }
  }

}
