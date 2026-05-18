Pathauto Enable Action
======================

This simple Drupal 10 module provides an Action plugin that enables the Pathauto "Generate automatic URL alias" flag for node entities. It is useful when migrated content retains existing aliases and you want to re-generate aliases for selected nodes.

## Features
- Provides a bulk action "Enable automatic URL alias (Pathauto)" for use in Content bulk operations.
- Sets the Pathauto `CREATE` state on selected nodes so aliases will be regenerated.
- Requires the "Administer nodes" permission to execute.

## Installation
1. Place this module in `web/modules/custom/pathauto_enable_action`.
2. Enable the module:
   ```bash
   drush en pathauto_enable_action -y
   ```
   Or via UI: Admin > Extend > (search for "Pathauto enable action") > Enable.

3. Clear caches:
   ```bash
   drush cache:rebuild
   ```

## Usage
1. Go to Admin > Content (or any content listing page).
2. Select one or more nodes using the checkboxes.
3. Under "Action", select "Enable automatic URL alias (Pathauto)".
4. Click "Apply to selected items".
5. The action sets the Pathauto flag; then use Pathauto's Bulk generate feature to regenerate aliases:
   - Go to Admin > Configuration > Search and metadata > URL aliases > Bulk generate.
   - Or run `drush pathauto:generate-aliases`.

## How It Works
- The action plugin sets `$node->path->pathauto = PathautoState::CREATE` on each selected node.
- This tells Pathauto to regenerate the alias the next time the node is saved or via bulk operations.
- The action does **not** immediately regenerate aliases—it only sets the flag. Use Pathauto bulk update afterward.

## Module Structure
- `pathauto_enable_action.info.yml` — Module metadata.
- `src/Plugin/Action/PathautoEnableAction.php` — The Action plugin implementation.
- `config/install/system.action.pathauto_enable_action.yml` — System action configuration entity.
- `pathauto_enable_action.permissions.yml` — Custom permissions (optional).

## Troubleshooting
- **Action doesn't appear in the Actions dropdown:**
  - Ensure the module is enabled: `drush pml --status=enabled | grep pathauto_enable_action`
  - Clear caches: `drush cache:rebuild`
  - Confirm you have "Administer nodes" permission.

- **Permission denied error:**
  - The action requires the "Administer nodes" permission. Assign this role to your user if needed.

## Testing
To run the module tests using DDEV and PHPUnit, run:
```bash
ddev exec env SIMPLETEST_DB="mysql://db:db@db/db" \
   vendor/bin/phpunit -c /var/www/html/web/core/phpunit.xml.dist \
   /var/www/html/web/modules/custom/pathauto_enable_action/tests
```
