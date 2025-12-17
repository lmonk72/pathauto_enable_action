<?php

namespace Drupal\Tests\pathauto_enable_action\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\pathauto\PathautoState;

/**
 * Tests the Pathauto Enable Action.
 *
 * @group pathauto_enable_action
 */
class PathautoEnableActionTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'user',
    'field',
    'node',
    'path',
    'path_alias',
    'token',
    'pathauto',
    'pathauto_enable_action',
  ];

  /**
   * Tests that the action sets pathauto CREATE state on a node.
   */
  public function testPathautoIsEnabled(): void {
    // Required entity storage.
    $this->installEntitySchema('user');
    $this->installEntitySchema('node');
    $this->installEntitySchema('path_alias');

    // Required non-entity tables.
    $this->installSchema('node', ['node_access']);

    // Create a content type.
    NodeType::create([
      'type' => 'article',
      'name' => 'Article',
    ])->save();

    // Create a node and force a known starting state.
    $node = Node::create([
      'type' => 'article',
      'title' => 'Test node',
    ]);
    $node->set('path', [
      'alias' => '',
      'pathauto' => PathautoState::SKIP,
    ]);
    $node->save();

    // Reload and confirm starting state.
    $node = Node::load($node->id());
    $this->assertEquals(PathautoState::SKIP, (int) $node->get('path')->first()->get('pathauto')->getValue());

    // Execute the action plugin.
    $action = $this->container
      ->get('plugin.manager.action')
      ->createInstance('pathauto_enable_action');
    $action->execute($node);

    // Reload and assert CREATE.
    $node = Node::load($node->id());
    $this->assertEquals(PathautoState::CREATE, (int) $node->get('path')->first()->get('pathauto')->getValue());
  }

}
