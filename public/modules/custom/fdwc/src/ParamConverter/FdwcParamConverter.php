<?php
namespace Drupal\fdwc\ParamConverter;

use Drupal\Core\ParamConverter\ParamConverterInterface;
use Symfony\Component\Routing\Route;
use Drupal\file\Entity\File;

/**
 * Class FdwcParamConverter contains methods to convert a path parameter to an object
 * @package Drupal\fdwc\ParamConverter
 */
class FdwcParamConverter implements ParamConverterInterface {

    /**
     * {@inheritdoc}
     */
    public function convert($value, $definition, $name, array $defaults) {
        return File::load($value);
    }

    /**
     * {@inheritdoc}
     */
    public function applies($definition, $name, Route $route) : bool {
        return (!empty($definition['type']) && $definition['type'] == 'file');
    }
}