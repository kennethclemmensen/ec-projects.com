<?php

declare(strict_types=1);

namespace Drupal\Tests\system\Functional\Menu;

use Drupal\Component\Utility\Html;
use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests local actions derived from router and added/altered via hooks.
 *
 * @group Menu
 */
class LocalActionTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['block', 'menu_test'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->drupalPlaceBlock('local_actions_block');
  }

  /**
   * Tests appearance of local actions.
   */
  public function testLocalAction(): void {
    $this->drupalGet('menu-test-local-action');
    // Ensure that both menu and route based actions are shown.
    $this->assertLocalAction([
      [Url::fromRoute('menu_test.local_action4'), 'My dynamic-title action'],
      [Url::fromRoute('menu_test.local_action4'), Html::escape("<script>alert('Welcome to the jungle!')</script>")],
      [Url::fromRoute('menu_test.local_action4'), Html::escape("<script>alert('Welcome to the derived jungle!')</script>")],
      [Url::fromRoute('menu_test.local_action2'), 'My hook_menu action'],
      [Url::fromRoute('menu_test.local_action3'), 'My YAML discovery action'],
      [Url::fromRoute('menu_test.local_action5'), 'Title override'],
    ]);
    // Test a local action title that changes based on a config value.
    $this->drupalGet(Url::fromRoute('menu_test.local_action6'));
    $this->assertLocalAction([
      [Url::fromRoute('menu_test.local_action5'), 'Original title'],
    ]);
    // Verify the expected cache tag in the response headers.
    $this->assertSession()->responseHeaderContains('x-drupal-cache-tags', 'config:menu_test.links.action');
    /** @var \Drupal\Core\Config\Config $config */
    $config = $this->container->get('config.factory')->getEditable('menu_test.links.action');
    $config->set('title', 'New title');
    $config->save();
    $this->drupalGet(Url::fromRoute('menu_test.local_action6'));
    $this->assertLocalAction([
      [Url::fromRoute('menu_test.local_action5'), 'New title'],
    ]);
  }

  /**
   * Asserts local actions in the page output.
   *
   * @param array $actions
   *   A list of expected action link titles, keyed by the hrefs.
   *
   * @internal
   */
  protected function assertLocalAction(array $actions): void {
    $elements = $this->xpath('//a[contains(@class, :class)]', [
      ':class' => 'button-action',
    ]);
    $index = 0;
    foreach ($actions as $action) {
      /** @var \Drupal\Core\Url $url */
      [$url, $title] = $action;
      // SimpleXML gives us the unescaped text, not the actual escaped markup,
      // so use a pattern instead to check the raw content.
      // This behavior is a bug in libxml, see
      // https://bugs.php.net/bug.php?id=49437.
      $this->assertSession()->responseMatches('@<a [^>]*class="[^"]*button-action[^"]*"[^>]*>' . preg_quote($title, '@') . '</@');
      $this->assertEquals($url->toString(), $elements[$index]->getAttribute('href'));
      $index++;
    }
  }

}
