# Prompt: Refactor Drupal Code to Use Dependency Injection

## Purpose
Convert legacy Drupal code using static service calls (`\Drupal::service()`, `\Drupal::database()`, etc.) to modern dependency injection patterns following PHP 8.3 and Drupal 10 best practices.

## Instructions

You are a Drupal 10 refactoring expert. Refactor the provided code to use proper dependency injection with the following requirements:

### Requirements

**Refactoring Scope:**
- **Current Code Type**: {CODE_TYPE} (e.g., Hook function, Controller, Service, Form)
- **Current File Path**: {FILE_PATH}
- **Services Used**: {LIST_SERVICES} (e.g., database, entity_type.manager, current_user, logger)
- **Target PHP Version**: 8.3
- **Target Drupal Version**: 10.x

**Refactoring Goals:**
1. **Remove Static Calls**: Eliminate all `\Drupal::service()`, `\Drupal::database()`, `\Drupal::config()`, etc.
2. **Add Dependency Injection**: Inject all dependencies via constructor
3. **Update Namespace**: Ensure proper PHP namespace if converting to class
4. **Add Type Hints**: Use strict typing on all parameters and returns
5. **Add PHPDoc**: Include comprehensive documentation
6. **Create/Update Services.yml**: Add service registration if needed

**Code Quality Standards:**
- Declare `strict_types=1` in all PHP files
- Use typed properties with `private readonly` for immutable dependencies
- Use `private readonly` for constructor-injected properties
- Implement all required interfaces (`ContainerFactoryPluginInterface`, etc.)
- Add `create()` static method for container-based instantiation
- Include error handling and logging
- Add comprehensive PHPDoc blocks
- Use proper return types and parameter types

**Best Practices:**
- Follow Drupal Coding Standards
- Apply Single Responsibility Principle
- Keep classes focused and lean
- Remove code duplication
- Use meaningful names
- Add configuration for injectable services
- Include usage examples

**Refactoring Patterns:**

### Pattern 1: Hook Function to Service Class
**Before:**
```php
function my_module_node_insert(EntityInterface $entity): void {
    $database = \Drupal::database();
    $logger = \Drupal::logger('my_module');

    $database->insert('my_table')
        ->fields(['nid' => $entity->id()])
        ->execute();

    $logger->info('Node created: @nid', ['@nid' => $entity->id()]);
}
```

**After:**
```php
<?php

declare(strict_types=1);

namespace Drupal\my_module\Service;

use Drupal\Core\Database\Connection;
use Psr\Log\LoggerInterface;

class NodeInsertService {

    public function __construct(
        private readonly Connection $database,
        private readonly LoggerInterface $logger,
    ) {}

    public function onNodeInsert(EntityInterface $entity): void {
        $this->database->insert('my_table')
            ->fields(['nid' => $entity->id()])
            ->execute();

        $this->logger->info('Node created: @nid', ['@nid' => $entity->id()]);
    }
}
```

### Pattern 2: Controller Using Static Calls
**Before:**
```php
class MyController extends ControllerBase {
    public function list(): array {
        $database = \Drupal::database();
        $entityTypeManager = \Drupal::entityTypeManager();
        $currentUser = \Drupal::currentUser();

        $result = $database->select('my_table', 'mt')
            ->fields('mt')
            ->execute();

        $items = $result->fetchAll();

        return ['#items' => $items];
    }
}
```

**After:**
```php
<?php

declare(strict_types=1);

namespace Drupal\my_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MyController extends ControllerBase {

    public function __construct(
        private readonly Connection $database,
        private readonly EntityTypeManagerInterface $entityTypeManager,
        private readonly AccountProxyInterface $currentUser,
    ) {}

    public static function create(ContainerInterface $container): self {
        return new self(
            $container->get('database'),
            $container->get('entity_type.manager'),
            $container->get('current_user'),
        );
    }

    public function list(): array {
        $result = $this->database->select('my_table', 'mt')
            ->fields('mt')
            ->execute();

        $items = $result->fetchAll();

        return ['#items' => $items];
    }
}
```

### Pattern 3: Form Class Static Calls
**Before:**
```php
class MyForm extends FormBase {
    public function buildForm(array $form, FormStateInterface $form_state): array {
        $config = \Drupal::config('my_module.settings');
        $configFactory = \Drupal::configFactory();

        $form['setting'] = [
            '#type' => 'textfield',
            '#default_value' => $config->get('setting'),
        ];

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state): void {
        \Drupal::configFactory()
            ->getEditable('my_module.settings')
            ->set('setting', $form_state->getValue('setting'))
            ->save();
    }
}
```

**After:**
```php
<?php

declare(strict_types=1);

namespace Drupal\my_module\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MyForm extends FormBase {

    public function __construct(
        private readonly ConfigFactoryInterface $configFactory,
    ) {}

    public static function create(ContainerInterface $container): self {
        return new self(
            $container->get('config.factory'),
        );
    }

    public function getFormId(): string {
        return 'my_module_my_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state): array {
        $config = $this->configFactory->get('my_module.settings');

        $form['setting'] = [
            '#type' => 'textfield',
            '#default_value' => $config->get('setting'),
        ];

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state): void {
        $this->configFactory
            ->getEditable('my_module.settings')
            ->set('setting', $form_state->getValue('setting'))
            ->save();
    }
}
```

### Pattern 4: Plugin with Static Calls
**Before:**
```php
class MyBlock extends BlockBase {
    public function build(): array {
        $entityTypeManager = \Drupal::entityTypeManager();
        $logger = \Drupal::logger('my_module');

        $nodes = $entityTypeManager->getStorage('node')
            ->loadByProperties(['status' => 1]);

        $logger->info('Loaded @count nodes', ['@count' => count($nodes)]);

        return ['#nodes' => $nodes];
    }
}
```

**After:**
```php
<?php

declare(strict_types=1);

namespace Drupal\my_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * My custom block plugin.
 *
 * @Block(
 *   id = "my_block",
 *   admin_label = @Translation("My Block"),
 * )
 */
class MyBlock extends BlockBase implements ContainerFactoryPluginInterface {

    public function __construct(
        array $configuration,
        string $plugin_id,
        mixed $plugin_definition,
        private readonly EntityTypeManagerInterface $entityTypeManager,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
    }

    public static function create(
        ContainerInterface $container,
        array $configuration,
        $plugin_id,
        $plugin_definition,
    ): self {
        return new self(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('entity_type.manager'),
            $container->get('logger.channel.my_module'),
        );
    }

    public function build(): array {
        $nodes = $this->entityTypeManager->getStorage('node')
            ->loadByProperties(['status' => 1]);

        $this->logger->info('Loaded @count nodes', ['@count' => count($nodes)]);

        return ['#nodes' => $nodes];
    }
}
```

### Pattern 5: Service with Mixed Approach
**Before:**
```php
class DataService {
    private Database $database;

    public function __construct(Connection $database) {
        $this->database = $database;
    }

    public function processData(int $id): array {
        $config = \Drupal::config('my_module.settings');
        $logger = \Drupal::logger('my_module');

        $data = $this->database->select('my_data', 'md')
            ->condition('id', $id)
            ->execute()
            ->fetchAssoc();

        if (!$data) {
            $logger->warning('Data not found: @id', ['@id' => $id]);
            return [];
        }

        return $data;
    }
}
```

**After:**
```php
<?php

declare(strict_types=1);

namespace Drupal\my_module\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use Psr\Log\LoggerInterface;

class DataService {

    public function __construct(
        private readonly Connection $database,
        private readonly ConfigFactoryInterface $configFactory,
        private readonly LoggerInterface $logger,
    ) {}

    public function processData(int $id): array {
        $config = $this->configFactory->get('my_module.settings');

        $data = $this->database->select('my_data', 'md')
            ->condition('id', $id)
            ->execute()
            ->fetchAssoc();

        if (!$data) {
            $this->logger->warning('Data not found: @id', ['@id' => $id]);
            return [];
        }

        return $data;
    }
}
```

---

## Common Static Calls to Replace

### Database
```
\Drupal::database()           → @database (Connection service)
\Drupal::db_select()          → $this->database->select()
```

### Configuration
```
\Drupal::config()             → @config.factory (ConfigFactoryInterface)
\Drupal::configFactory()      → @config.factory
```

### Entities
```
\Drupal::entityTypeManager()  → @entity_type.manager (EntityTypeManagerInterface)
\Drupal::entityManager()      → @entity_type.manager
```

### Users & Current User
```
\Drupal::currentUser()        → @current_user (AccountProxyInterface)
\Drupal::service('current_user')  → @current_user
```

### Modules
```
\Drupal::moduleHandler()      → @module_handler (ModuleHandlerInterface)
```

### Logger
```
\Drupal::logger()             → @logger.channel.{module_name} (LoggerInterface)
```

### State
```
\Drupal::state()              → @state (StateInterface)
```

### Cache
```
\Drupal::cache()              → @cache.default (CacheBackendInterface)
\Drupal::cache('bin_name')    → @cache.bin_name
```

### HTTP Client
```
\Drupal::httpClient()         → @http_client (ClientInterface)
```

### Render Cache
```
\Drupal::service('render_cache')  → @render_cache
```

---

## Services.yml Registration

After refactoring to use dependency injection, register services:

```yaml
services:
  my_module.my_service:
    class: Drupal\my_module\Service\MyService
    arguments:
      - '@database'
      - '@entity_type.manager'
      - '@logger.channel.my_module'
    tags:
      - { name: 'needs_destruction' }

  my_module.event_subscriber:
    class: Drupal\my_module\Event\MyEventSubscriber
    arguments:
      - '@my_module.my_service'
    tags:
      - { name: 'event_subscriber' }
```

---

## Hook Implementation Conversion

### Before: Static Calls in Hook
```php
function my_module_node_insert(EntityInterface $entity): void {
    $database = \Drupal::database();
    $logger = \Drupal::logger('my_module');
    // ...
}
```

### After: Delegate to Service
```php
<?php

declare(strict_types=1);

/**
 * Implements hook_ENTITY_TYPE_insert().
 */
function my_module_node_insert(EntityInterface $entity): void {
    /** @var \Drupal\my_module\Service\NodeService $nodeService */
    $nodeService = \Drupal::service('my_module.node_service');
    $nodeService->onNodeInsert($entity);
}
```

**OR (Better): Use Event Subscriber**
```php
<?php

declare(strict_types=1);

namespace Drupal\my_module\Event;

use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class NodeEventSubscriber implements EventSubscriberInterface {

    public function __construct(
        private readonly EntityTypeManagerInterface $entityTypeManager,
    ) {}

    public static function getSubscribedEvents(): array {
        return [
            'entity_insert' => 'onEntityInsert',
        ];
    }

    public function onEntityInsert(EntityInterface $entity): void {
        if ($entity->getEntityTypeId() === 'node') {
            // Handle node insertion
        }
    }
}
```

---

## Refactoring Steps

1. **Identify Static Calls**: Find all `\Drupal::` static calls in the code
2. **List Dependencies**: Create a list of all required services
3. **Add Constructor**: Add constructor with typed parameters for all dependencies
4. **Add Type Hints**: Add return types and parameter types to all methods
5. **Add create() Method**: Implement `create()` for classes instantiated by container
6. **Replace Calls**: Replace `\Drupal::service('x')` with `$this->serviceProperty`
7. **Add PHPDoc**: Document all methods and classes
8. **Register Service**: Add service to `module_name.services.yml`
9. **Test**: Verify all functionality works after refactoring
10. **Update Module Hook**: If applicable, update hook implementations

---

## Validation Checklist

### Code Structure
- [ ] Strict types declared (`declare(strict_types=1);`)
- [ ] Proper namespace and class name
- [ ] Correct base class extended
- [ ] All required imports added
- [ ] Constructor includes all dependencies
- [ ] Constructor parameters are typed
- [ ] `create()` method implemented (if needed)
- [ ] All dependencies are `private readonly`

### Code Quality
- [ ] All methods have return types
- [ ] All parameters have type hints
- [ ] PHPDoc blocks on all methods
- [ ] @param, @return, @throws documented
- [ ] No `\Drupal::` static calls remain
- [ ] No hardcoded service strings in code
- [ ] Error handling implemented
- [ ] Logging where appropriate

### Services Configuration
- [ ] Service registered in services.yml
- [ ] All dependencies injected as arguments
- [ ] Service class path correct
- [ ] Tags added if needed
- [ ] Service ID follows naming convention

### Testing
- [ ] All existing functionality preserved
- [ ] No new warnings or errors
- [ ] Tested with different configurations
- [ ] Backward compatibility maintained (if required)
- [ ] Code style follows standards

---

## Best Practices

### DO:
- ✅ Use constructor dependency injection
- ✅ Type hint all parameters and returns
- ✅ Use `private readonly` for immutable dependencies
- ✅ Use meaningful service names
- ✅ Keep services focused on single responsibility
- ✅ Document dependencies in PHPDoc
- ✅ Register services in services.yml
- ✅ Use event subscribers instead of hooks when possible

### DON'T:
- ❌ Use `\Drupal::service()` in classes
- ❌ Mix static calls with dependency injection
- ❌ Use `global` variables
- ❌ Use file_get_contents() for configuration
- ❌ Create multiple instances of services
- ❌ Use static methods for service access
- ❌ Inject the entire container
- ❌ Skip type hints

---

## Common Issues & Solutions

### Issue: Circular Dependencies
```php
// ❌ Problem: Service A needs Service B, Service B needs Service A
services:
  my_module.service_a:
    arguments:
      - '@my_module.service_b'
  my_module.service_b:
    arguments:
      - '@my_module.service_a'

// ✅ Solution: Inject container or use lazy services
services:
  my_module.service_a:
    arguments:
      - '@service_container'
```

### Issue: Hook Implementation Needs Service
```php
// ❌ Problem: Using service in hook directly
function my_module_hook(): void {
    $service = \Drupal::service('my_module.service');
}

// ✅ Solution: Delegate to service or event subscriber
function my_module_hook(): void {
    \Drupal::service('my_module.hook_handler')->handle();
}
```

### Issue: Plugin with Services
```php
// ✅ Correct: Implement ContainerFactoryPluginInterface
class MyPlugin extends PluginBase implements ContainerFactoryPluginInterface {
    public function __construct(..., Service $service) {
        parent::__construct(...);
        $this->service = $service;
    }

    public static function create(ContainerInterface $container, ...) {
        return new self(..., $container->get('service_id'));
    }
}
```

---

## Refactoring Output Requirements

1. **Refactored PHP File** with:
   - Strict types declaration
   - Proper namespace and imports
   - Constructor with dependency injection
   - All static calls replaced
   - Comprehensive PHPDoc blocks
   - Type hints throughout

2. **Services.yml Entry** - Service registration (if needed)

3. **Hook Implementation** (if converting from hooks) - Delegation code

4. **Migration Guide** - Steps to integrate refactored code

5. **Testing Notes** - What to verify after refactoring

---

## Example Refactoring Request

```
Current Code Type: Controller
Current File Path: src/Controller/SearchController.php
Services Used:
  - database
  - entity_type.manager
  - current_user
  - logger
  - config.factory

Key Methods:
  - search(): Execute search query
  - buildResultsPage(): Render results
  - getUserPreferences(): Get user settings
```

---

## Integration Notes

- Update any code that instantiates the refactored class
- Update tests to inject mock services
- Update documentation if API changed
- Run full test suite after refactoring
- Monitor logs for service resolution errors
- Clear caches after deployment

---

## Quick Reference: Service IDs

```yaml
# Core Services
@database                    # Drupal\Core\Database\Connection
@entity_type.manager         # Drupal\Core\Entity\EntityTypeManagerInterface
@current_user                # Drupal\Core\Session\AccountProxyInterface
@config.factory              # Drupal\Core\Config\ConfigFactoryInterface
@module_handler              # Drupal\Core\Extension\ModuleHandlerInterface
@state                       # Drupal\Core\State\StateInterface
@cache.default               # Drupal\Core\Cache\CacheBackendInterface
@http_client                 # GuzzleHttp\ClientInterface
@logger.channel.{module}     # Psr\Log\LoggerInterface

# Common Custom Services
@event_dispatcher            # Symfony\Component\EventDispatcher\EventDispatcherInterface
@router                      # Drupal\Core\Routing\RouteProviderInterface
@path.alias_manager          # Drupal\Core\Path\AliasManager
@form_builder                # Drupal\Core\Form\FormBuilderInterface
@messenger                   # Drupal\Core\Messenger\MessengerInterface
```

---

## Validation Checklist for Refactoring

- [ ] All `\Drupal::` static calls removed
- [ ] All dependencies injected via constructor
- [ ] All properties are `private readonly`
- [ ] All parameters and returns have type hints
- [ ] PHPDoc blocks present and accurate
- [ ] `create()` method implemented (for plugins/forms/controllers)
- [ ] Services registered in services.yml
- [ ] No circular dependencies
- [ ] Tests updated and passing
- [ ] Code follows Drupal standards
- [ ] Backward compatibility maintained
- [ ] Documentation updated

