<?php

namespace Drupal\pathauto_enable_action\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Action\ActionInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\pathauto\PathautoState;

/**
 * Provides an action to enable Pathauto generation for nodes.
 *
 * @Action(
 *   id = "pathauto_enable_action",
 *   label = @Translation("Enable automatic URL alias (Pathauto)"),
 *   type = "node",
 *   requirements = {
 *     "_permission" = "administer nodes",
 *   }
 * )
 */
class PathautoEnableAction extends ActionBase implements ActionInterface
{

    /**
     * {@inheritdoc}
     */

    // Simple execute logic
    public function execute($entity = NULL)
    {
        if ($entity && method_exists($entity, 'path') && isset($entity->path)) {
            $entity->path->pathauto = PathautoState::CREATE;
            $entity->save();
        }
    }

    // Straightforward access using administer nodes permission
    public function access($object, ?AccountInterface $account = NULL, $return_as_object = FALSE)
    {
        $result = AccessResult::allowedIfHasPermission($account, 'administer nodes');
        return $return_as_object ? $result : $result->isAllowed();
    }
}
