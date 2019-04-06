<?php
namespace Drupal\fdwc\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Drupal\file\Entity\File;

/**
 * Class FdwcController contains methods to handle file downloads
 * @package Drupal\fdwc\Controller
 */
class FdwcController extends ControllerBase {

    /**
     * Download a file
     *
     * @param File $file the file to download
     * @return BinaryFileResponse a HTTP response that downloads the file
     */
    public function download(File $file) : BinaryFileResponse {
        if(!is_null($file)) {
            $this->updateCounterField();
            $response = new BinaryFileResponse($file->getFileUri());
            $response->trustXSendfileTypeHeader();
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $file->getFilename());
            return $response;
        } else {
            throw new NotFoundHttpException();
        }
    }

    /**
     * Update the counter field
     */
    private function updateCounterField() : void {
        $nodeId = 31;
        $node = Node::load($nodeId);
        if($node !== null) {
            $fieldName = 'field_download_counter';
            if($node->hasField($fieldName)) {
                $field = $node->get($fieldName);
                $newValue = $field->getValue()[0]['value'] + 1;
                $field->setValue($newValue);
                try {
                    $node->save();
                } catch(EntityStorageException $ex) {

                }
            }
        }
    }
}