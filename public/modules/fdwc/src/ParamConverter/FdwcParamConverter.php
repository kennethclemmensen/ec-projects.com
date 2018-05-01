<?php

namespace Drupal\fdwc\ParamConverter;

use Drupal\Core\ParamConverter\ParamConverterInterface;
use Symfony\Component\Routing\Route;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * The FdwcParameterConverter implements the ParamConverterInterface interface.
 */
class FdwcParamConverter implements ParamConverterInterface {

  /**
   * The storage handler class for files.
   *
   * @var \Drupal\file\FileStorage
   */
  private $fileStorage;

  /**
   * Class constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity
   *   The Entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity) {
    $this->fileStorage = $entity->getStorage('file');
  }

  /**
   * Converts path variables to their corresponding objects.
   *
   * @param mixed $value
   *   The raw value.
   * @param mixed $definition
   *   The parameter definition provided in the route options.
   * @param string $name
   *   The name of the parameter.
   * @param array $defaults
   *   The route defaults array.
   *
   * @return \Drupal\file\Entity\File
   *   The converted parameter value.
   */
  public function convert($value, $definition, $name, array $defaults) {
    return $this->fileStorage->load($value);
  }

  /**
   * Determines if the converter applies to a specific route and variable.
   *
   * @param mixed $definition
   *   The parameter definition provided in the route options.
   * @param string $name
   *   The name of the parameter.
   * @param \Symfony\Component\Routing\Route $route
   *   The route to consider attaching to.
   *
   * @return bool
   *   TRUE if the converter applies to the passed route and parameter, FALSE
   *   otherwise.
   */
  public function applies($definition, $name, Route $route) {
    return (!empty($definition['type']) && $definition['type'] == 'file');
  }

}
