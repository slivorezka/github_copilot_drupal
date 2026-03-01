You are a Drupal 10 refactoring expert. Refactor the provided code to use proper dependency injection, replacing all static service calls (`\Drupal::service()`, `\Drupal::database()`, etc.) with modern DI patterns following PHP 8.3 and Drupal 10 best practices.

Analyze and refactor the following code or file: $ARGUMENTS

If a file path is provided, read the file and refactor it. If code is provided directly, refactor it in place.

## Refactoring Goals

1. **Remove all static calls**: Eliminate `\Drupal::service()`, `\Drupal::database()`, `\Drupal::config()`, `\Drupal::entityTypeManager()`, `\Drupal::currentUser()`, `\Drupal::logger()`, etc.
2. **Add constructor DI**: Inject all dependencies via constructor with `private readonly` typed properties
3. **Add `create()` method**: For classes instantiated by the container (controllers, forms, plugins)
4. **Add type hints**: Strict typing on all parameters and return types
5. **Add PHPDoc**: Comprehensive documentation
6. **Create/update services.yml**: Service registration if needed

## Static Call → Service Mapping

```
\Drupal::database()           → @database (Connection)
\Drupal::config()             → @config.factory (ConfigFactoryInterface)
\Drupal::configFactory()      → @config.factory (ConfigFactoryInterface)
\Drupal::entityTypeManager()  → @entity_type.manager (EntityTypeManagerInterface)
\Drupal::currentUser()        → @current_user (AccountProxyInterface)
\Drupal::moduleHandler()      → @module_handler (ModuleHandlerInterface)
\Drupal::logger()             → @logger.channel.{module} (LoggerInterface)
\Drupal::state()              → @state (StateInterface)
\Drupal::cache()              → @cache.default (CacheBackendInterface)
\Drupal::httpClient()         → @http_client (ClientInterface)
\Drupal::service('messenger') → @messenger (MessengerInterface)
\Drupal::formBuilder()        → @form_builder (FormBuilderInterface)
```

## Refactoring Patterns

### Pattern: Controller
- Extend `ControllerBase`
- Add constructor with DI
- Add `create()` static method
- Replace all `\Drupal::` calls with `$this->property`

### Pattern: Form
- Extend `FormBase` or `ConfigFormBase`
- Add constructor with DI
- Add `create()` static method

### Pattern: Plugin (Block, etc.)
- Implement `ContainerFactoryPluginInterface`
- Constructor: `(array $configuration, string $plugin_id, mixed $plugin_definition, ...services)`
- Call `parent::__construct($configuration, $plugin_id, $plugin_definition)` first
- Add `create()` with container + plugin params

### Pattern: Service
- Pure constructor DI (no `create()` method needed)
- Register in `*.services.yml`

### Pattern: Hook → Service Delegation
If converting hook functions with static calls:
```php
// Hook file delegates to service.
function my_module_node_insert(EntityInterface $entity): void {
    \Drupal::service('my_module.node_service')->onNodeInsert($entity);
}
```

## Output Requirements

Generate all of the following:

1. **Refactored PHP File** — Complete file with:
   - `declare(strict_types=1);`
   - Proper namespace and imports
   - Constructor with `private readonly` dependencies
   - `create()` method (if applicable)
   - All static calls replaced with `$this->property`
   - Comprehensive PHPDoc blocks

2. **Services.yml Entry** — If a new service is created:
```yaml
services:
  {module_name}.{service_name}:
    class: Drupal\{module_name}\Service\{ServiceName}
    arguments:
      - '@dependency1'
      - '@dependency2'
```

3. **Migration Notes** — Steps to integrate:
   - Files changed
   - Services added
   - Tests to update
   - Caches to clear

4. **Before/After Summary** — What changed and why

## Validation Checklist

- [ ] All `\Drupal::` static calls removed from classes
- [ ] All dependencies injected via constructor
- [ ] All properties are `private readonly`
- [ ] All parameters and returns have type hints
- [ ] PHPDoc blocks present and accurate
- [ ] `create()` method implemented (for plugins/forms/controllers)
- [ ] Services registered in services.yml
- [ ] No circular dependencies introduced
- [ ] Backward compatibility maintained
- [ ] Code follows Drupal coding standards

