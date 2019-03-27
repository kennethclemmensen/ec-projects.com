<?php
namespace Drupal\fdwc\ParamConverter;

use Drupal\Core\ParamConverter\ParamConverterInterface;
use Symfony\Component\Routing\Route;
use Drupal\file\Entity\File;

/**
 * Class FdwcParamConverter
 * @package Drupal\fdwc\ParamConverter
 */
class FdwcParamConverter implements ParamConverterInterface {

    /**
     *
     *
     * @param mixed $value
     * @param mixed $definition
     * @param string $name
     * @param array $defaults
     * @return \Drupal\Core\Entity\EntityInterface|File|mixed|null
     */
    public function convert($value, $definition, $name, array $defaults) {
        return File::load($value);
    }

    /**
     *
     *
     * @param mixed $definition
     * @param string $name
     * @param Route $route
     * @return bool
     */
    public function applies($definition, $name, Route $route) : bool {
        return (!empty($definition['type']) && $definition['type'] == 'file');
    }
}