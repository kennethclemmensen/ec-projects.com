<?php

declare(strict_types=1);

namespace Drupal\KernelTests\Core\Entity;

use Drupal\language\Entity\ConfigurableLanguage;

/**
 * Tests correct field method invocation order.
 *
 * @group Entity
 */
class ContentEntityFieldMethodInvocationOrderTest extends EntityKernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['language', 'system', 'entity_test'];

  /**
   * The EntityTest entity type storage.
   *
   * @var \Drupal\Core\Entity\ContentEntityStorageInterface
   */
  protected $entityTestFieldMethodsStorage;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Enable an additional language.
    ConfigurableLanguage::createFromLangcode('de')->save();
    ConfigurableLanguage::createFromLangcode('fr')->save();

    $this->installEntitySchema('entity_test_field_methods');

    $this->entityTestFieldMethodsStorage = $this->entityTypeManager->getStorage('entity_test_field_methods');
  }

  /**
   * Tests correct field method invocation order.
   */
  public function testFieldMethodInvocationOrder(): void {

    // Create a test entity.
    $entity = $this->entityTestFieldMethodsStorage->create([
      'name' => $this->randomString(),
      'langcode' => 'de',
    ]);
    $entity->save();

    $entity->addTranslation('fr')
      ->save();

    // Reset the current value of the test field.
    foreach (['de', 'fr'] as $langcode) {
      $entity->getTranslation($langcode)->test_invocation_order->value = 0;
    }
    $entity->getTranslation('de')
      ->save();
    // Verify that the field presave method has been invoked in the correct
    // entity translation order.
    $this->assertGreaterThan($entity->getTranslation('de')->test_invocation_order->value, $entity->getTranslation('fr')->test_invocation_order->value);

    // Reset the current value of the test field.
    foreach (['de', 'fr'] as $langcode) {
      $entity->getTranslation($langcode)->test_invocation_order->value = 0;
    }
    $entity->getTranslation('fr')
      ->save();
    // Verify that the field presave method has been invoked in the correct
    // entity translation order.
    $this->assertGreaterThan($entity->getTranslation('fr')->test_invocation_order->value, $entity->getTranslation('de')->test_invocation_order->value);
  }

}
