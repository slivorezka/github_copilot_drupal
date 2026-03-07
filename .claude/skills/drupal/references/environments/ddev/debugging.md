### Debugging in DDEV

#### Core Debugging & Information Commands
| Command                          | Purpose                                                                 | Why it's useful for debugging                                      |
|----------------------------------|-------------------------------------------------------------------------|--------------------------------------------------------------------|
| `ddev exec drush status`         | Shows Drupal root, site path, database connection, Drush version, etc. | Quickly verify that DDEV is pointing to the correct site and DB is connected. |
| `ddev exec drush core-status`    | Same as above but more detailed in newer versions.                      |                                                                    |
| `ddev exec drush watchdog:show`  | Lists recent log messages (dblog entries).                              | Primary command to read the Drupal error/log messages without going to /admin/reports/dblog. Supports filters: `--severity=Error`, `--type=php`, etc. |
| `ddev exec drush watchdog:delete all` | Clears the watchdog log.                                                | Useful when logs become huge and slow down watchdog operations. |
| `ddev exec drush sql:query "SELECT * FROM watchdog ORDER BY wid DESC LIMIT 50"` | Direct SQL access to logs when the database is very large.             | Faster than watchdog:show on sites with millions of log entries. |

#### Cache Debugging
| Command                          | Purpose                                                                 |
|----------------------------------|-------------------------------------------------------------------------|
| `ddev exec drush cache:rebuild`  | Rebuilds all caches (equivalent to "drush cc all" in D7).              |
| `ddev exec drush cr`             | Alternative cache rebuild command.                                      |
| `ddev exec drush cache:get <bin>:<cid>` | Retrieve a specific cache item (e.g., `ddev exec drush cache:get config:core.extension`). |
| `ddev exec drush cache:clear <bin>` | Clear only one cache bin (render, config, discovery, etc.).            |

#### Configuration Debugging
| Command                                      | Purpose                                                                 |
|----------------------------------------------|-------------------------------------------------------------------------|
| `ddev exec drush config:get <name>`          | Show a single configuration value (e.g., `ddev exec drush config:get system.site`). |
| `ddev exec drush config:set <name> <key> <value>` | Temporarily change a config value without using the UI.                 |
| `ddev exec drush config:export`              | Export active config to sync directory.                                 |
| `ddev exec drush config:import`              | Import config – very useful to test if config issues cause errors.      |
| `ddev exec drush config:delete <name>`       | Remove a config object (helps when orphaned config causes fatal errors).|

#### Module/Theming Debugging
| Command                                | Purpose                                                                 |
|----------------------------------------|-------------------------------------------------------------------------|
| `ddev exec drush pm:list --type=module --status=enabled` | List enabled modules.                                                  |
| `ddev exec drush pm:enable <module>`   | Enable a module.                                                       |
| `ddev exec drush pm:uninstall <module>` | Fully uninstall a module (removes config and data).                    |
| `ddev exec drush pm:uninstall` without arguments → interactive mode is excellent for disabling suspected problematic modules quickly. |
| `ddev exec drush theme:debug` (Drupal 9.4+) | Lists all theme suggestions for a given route or render array.         |

#### Database & Entity Debugging
| Command                                      | Purpose                                                                 |
|----------------------------------------------|-------------------------------------------------------------------------|
| `ddev exec drush sql:connect`                | Outputs the CLI command to connect to the DB (useful for manual queries). |
| `ddev exec drush sql:query`                  | Run arbitrary SQL.                                                      |
| `ddev exec drush entity:info`                | Show entity type definitions (useful when entity schema errors occur). |
| `ddev exec drush php`                        | Opens an interactive PHP shell with Drupal bootstrapped (like `ddev exec drush php:eval`). |
| `ddev exec drush php:eval "code"`            | Execute arbitrary PHP code in Drupal context (great for quick debugging). Example: `ddev exec drush php:eval "dpm(\Drupal::state()->get('system.cron_last'));"` (with Devel) |

#### Development & Error Reproduction
| Command                                | Purpose                                                                 |
|----------------------------------------|-------------------------------------------------------------------------|
| `ddev exec drush php:eval "var_dump(function_exists('my_problematic_function'));"` | Quick test if a function exists or what it returns.                     |
| `ddev exec drush state:edit` / `ddev exec drush state:get/set/delete` | Inspect or override Drupal state values (often used by broken modules).|
| `ddev exec drush variable:get/set/delete` (D7 only) | Legacy equivalent of state commands.                                    |
| `ddev exec drush twig:debug`           | Turn Twig debugging on/off and verify template suggestions.            |
| `ddev exec drush eval`                 | Same as above (alias of php:eval).                                      |

#### Performance & Query Debugging
| Command                                | Purpose                                                                 |
|----------------------------------------|-------------------------------------------------------------------------|
| `ddev exec drush sql:query --db-prefix` | See queries with table prefixes expanded (helps reading raw SQL).      |
| Enable Devel + `ddev exec drush kint` or `ddev exec drush dpm()` in code → instant output in terminal. |

#### DDEV-Specific Debugging
```bash
# Enable Xdebug debugging
# Add to .ddev/config.yaml:
# xdebug_enabled: true

# DDEV container debugging
ddev logs -f web                       # Follow web container logs
ddev logs -f db                        # Follow database container logs
ddev describe                          # Show environment details and status

# Access PHP error logs
ddev exec tail -f /var/log/apache2/error.log

# Debugging functions (use with devel module)
ddev exec php -r "kint(\Drupal::config('system.site')->get());"

# Database connection debugging
ddev exec drush sql:connect            # Test database connection
ddev describe                          # Check environment status
```

### Performance Profiling in DDEV
```bash
# Performance analysis
ddev exec drush cr                     # Rebuild caches
ddev exec drush sql:query "EXPLAIN ANALYZE SELECT ..."  # Query analysis
ddev exec drush site:status           # System status check

# Use Webprofiler module for detailed profiling
# Access at https://my-drupal-project.ddev.site/admin/config/development/devel/webprofiler
```
