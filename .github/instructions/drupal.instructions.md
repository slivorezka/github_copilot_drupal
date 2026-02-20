# Comprehensive Drupal 10 Instructions

## 1. Project Foundation

### Overview
- **Framework**: Drupal 10.x
- **PHP Version**: 8.3
- **Stack**: LAMP (Linux, Apache, MySQL/MariaDB, PHP)
- **Goal**: Clean, modular, testable, and secure code adhering to Drupal best practices

### Key Principles
- **DRY (Don't Repeat Yourself)**: Reuse code through hooks, plugins, and services
- **SOLID**: Single Responsibility, Open/Closed, Liskov Substitution, Interface Segregation, Dependency Inversion
- **Security First**: Sanitize input, escape output, check permissions
- **Testability**: Write code that's easy to test with PHPUnit
- **Drupal Standards**: Follow official [Drupal Coding Standards](https://www.drupal.org/docs/develop/standards)

---

## 2. Module Development Structure

### Anatomy of a Custom Module

```
my_module/
├── src/
│   ├── Controller/
│   │   └── MyController.php
│   ├── Service/
│   │   └── MyService.php
│   ├── Form/
│   │   └── MyForm.php
│   ├── Plugin/
│   │   ├── Block/
│   │   ├── Field/
│   │   └── views/
│   ├── Entity/
│   │   └── MyEntity.php
│   ├── Event/
│   │   └── MyEventSubscriber.php
│   └── Hooks/
│       └── MyHooks.php
├── templates/
│   └── my-template.html.twig
├── css/
│   └── my-module.scss
├── js/
│   └── my-module.js
├── tests/
│   └── src/
│       ├── Unit/
│       └── Kernel/
├── my_module.info.yml
├── my_module.services.yml
├── my_module.routing.yml
├── my_module.permissions.yml
├── my_module.links.menu.yml
└── my_module.libraries.yml
```

### Module Files Description

#### `module_name.info.yml`
Defines module metadata and dependencies.

```yaml
name: My Module
description: Provides custom functionality
package: Custom
type: module
core_version_requirement: ^10.0
dependencies:
  - drupal:node
  - drupal:user
```

#### `module_name.services.yml`
Registers all services with dependency injection.

```yaml
services:
  my_module.service_name:
    class: Drupal\my_module\Service\MyService
    arguments:
      - '@database'
      - '@entity_type.manager'
    tags:
      - { name: 'needs_destruction' }
```

#### `module_name.routing.yml`
Defines routes for controllers.

```yaml
my_module.my_route:
  path: '/my-path/{id}'
  defaults:
    _controller: '\Drupal\my_module\Controller\MyController::myAction'
    _title: 'My Page'
  requirements:
    _permission: 'access content'
    id: '\d+'
```

#### `module_name.permissions.yml`
Defines custom permissions.

```yaml
'my custom permission':
  title: 'My Custom Permission'
  description: 'Description of the permission'
  restrict access: false
```

#### `module_name.libraries.yml`
Registers CSS, JS, and dependencies.

```yaml
my_library:
  css:
    theme:
      css/my-module.css: {}
  js:
    js/my-module.js:
      attributes:
        defer: true
  dependencies:
    - core/drupal
    - core/jquery
```

---

## 3. Hooks & Events

### Hook Implementation

Hooks are entry points for modules to interact with Drupal. Implement hooks in a dedicated file or class.

```php
<?php

declare(strict_types=1);

namespace Drupal\my_module\Hooks;

use Drupal\Core\Entity\EntityInterface;

/**
 * Implements hook_ENTITY_TYPE_insert() for nodes.
 */
function my_module_node_insert(EntityInterface $entity): void {
    // React to node creation
}

/**
 * Implements hook_form_FORM_ID_alter().
 */
function my_module_form_node_form_alter(array &$form, array &$form_state): void {
    // Modify node form
}

/**
 * Implements hook_preprocess_HOOK().
 */
function my_module_preprocess_node(array &$variables): void {
    // Prepare variables for node template
}
```

### Event Subscribers

Use event subscribers for custom events instead of hooks when possible.

```php
<?php

declare(strict_types=1);

namespace Drupal\my_module\Event;

use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event subscriber for custom events.
 */
class MyEventSubscriber implements EventSubscriberInterface {

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array {
        return [
            'my_module.custom_event' => 'onCustomEvent',
        ];
    }

    /**
     * Handles custom event.
     */
    public function onCustomEvent(CustomEvent $event): void {
        // Handle event
    }
}
```

Register in `my_module.services.yml`:

```yaml
services:
  my_module.event_subscriber:
    class: Drupal\my_module\Event\MyEventSubscriber
    tags:
      - { name: 'event_subscriber' }
```

---

## 4. Controllers & Routing

### Controller Structure

Controllers should be lightweight request handlers that delegate to services.

```php
<?php

declare(strict_types=1);

namespace Drupal\my_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\my_module\Service\MyService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Provides controller methods for my module.
 */
class MyController extends ControllerBase {

    private MyService $myService;

    /**
     * Constructor.
     */
    public function __construct(MyService $myService) {
        $this->myService = $myService;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container): self {
        return new self(
            $container->get('my_module.service')
        );
    }

    /**
     * Responds with a page.
     */
    public function page(): array {
        $data = $this->myService->getData();
        return [
            '#theme' => 'my_template',
            '#data' => $data,
        ];
    }

    /**
     * Responds with JSON.
     */
    public function jsonResponse(): JsonResponse {
        $data = $this->myService->getData();
        return new JsonResponse($data);
    }
}
```

### Routing Definition

```yaml
my_module.page:
  path: '/my-page'
  defaults:
    _controller: '\Drupal\my_module\Controller\MyController::page'
    _title: 'My Page'
  requirements:
    _permission: 'access content'

my_module.api:
  path: '/api/my-data/{id}'
  defaults:
    _controller: '\Drupal\my_module\Controller\MyController::jsonResponse'
  requirements:
    _permission: 'access content'
    id: '\d+'
```

---

## 5. Services & Dependency Injection

### Creating Services

Services encapsulate business logic and are injected where needed.

```php
<?php

declare(strict_types=1);

namespace Drupal\my_module\Service;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Service for data processing.
 */
class DataProcessor {

    /**
     * Constructor.
     */
    public function __construct(
        private readonly Connection $database,
        private readonly EntityTypeManagerInterface $entityTypeManager,
        private readonly LoggerInterface $logger,
    ) {}

    /**
     * Processes data.
     *
     * @param array $data
     *   Data to process.
     *
     * @return array
     *   Processed data.
     */
    public function processData(array $data): array {
        try {
            $this->logger->info('Processing data');

            // Business logic here
            $result = [];

            return $result;
        } catch (\Exception $e) {
            $this->logger->error('Error: @message', ['@message' => $e->getMessage()]);
            throw $e;
        }
    }
}
```

### Registering Services

In `my_module.services.yml`:

```yaml
services:
  my_module.data_processor:
    class: Drupal\my_module\Service\DataProcessor
    arguments:
      - '@database'
      - '@entity_type.manager'
      - '@logger.channel.my_module'
```

### Using Services in Classes

```php
public function __construct(
    private DataProcessor $dataProcessor
) {}
```

---

## 6. Forms & Form Handling

### Form Class

Extend `FormBase` for custom forms.

```php
<?php

declare(strict_types=1);

namespace Drupal\my_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\my_module\Service\DataProcessor;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for data submission.
 */
class MyForm extends FormBase {

    public function __construct(
        private DataProcessor $dataProcessor
    ) {}

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container): self {
        return new self(
            $container->get('my_module.data_processor')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId(): string {
        return 'my_module_my_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state): array {
        $form['name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Name'),
            '#required' => TRUE,
        ];

        $form['email'] = [
            '#type' => 'email',
            '#title' => $this->t('Email'),
            '#required' => TRUE,
        ];

        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state): void {
        $email = $form_state->getValue('email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $form_state->setErrorByName('email', $this->t('Invalid email'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state): void {
        $data = $form_state->getValues();
        $this->dataProcessor->processData($data);
        $this->messenger()->addStatus($this->t('Form submitted successfully'));
    }
}
```

### Hook Alters for Forms

```php
/**
 * Implements hook_form_FORM_ID_alter().
 */
function my_module_form_node_form_alter(array &$form, FormStateInterface $form_state): void {
    // Modify the node form
    $form['title']['widget'][0]['value']['#title'] = t('Custom Title Label');
}
```

---

## 7. Entities & Entity API

### Working with Entities

```php
<?php

declare(strict_types=1);

namespace Drupal\my_module\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;

class EntityService {

    public function __construct(
        private EntityTypeManagerInterface $entityTypeManager
    ) {}

    /**
     * Load a node by ID.
     */
    public function getNode(int $nid): ?object {
        return $this->entityTypeManager
            ->getStorage('node')
            ->load($nid);
    }

    /**
     * Load multiple nodes.
     */
    public function getNodes(array $nids): array {
        return $this->entityTypeManager
            ->getStorage('node')
            ->loadMultiple($nids);
    }

    /**
     * Create and save a new node.
     */
    public function createNode(array $values): object {
        $node = $this->entityTypeManager
            ->getStorage('node')
            ->create($values);
        $node->save();
        return $node;
    }

    /**
     * Query entities.
     */
    public function queryNodes(string $type): array {
        $query = $this->entityTypeManager
            ->getStorage('node')
            ->getQuery();

        return $query
            ->condition('type', $type)
            ->condition('status', 1)
            ->sort('created', 'DESC')
            ->execute();
    }
}
```

---

## 8. Database & Queries

### Using the Database API

Always use prepared statements to prevent SQL injection.

```php
<?php

declare(strict_types=1);

namespace Drupal\my_module\Service;

use Drupal\Core\Database\Connection;

class DatabaseService {

    public function __construct(
        private Connection $database
    ) {}

    /**
     * Insert a record.
     */
    public function insertRecord(array $fields): int {
        return $this->database->insert('my_module_table')
            ->fields($fields)
            ->execute();
    }

    /**
     * Select records.
     */
    public function getRecords(int $limit = 10): array {
        return $this->database->select('my_module_table', 'mmt')
            ->fields('mmt')
            ->condition('mmt.status', 1)
            ->range(0, $limit)
            ->orderBy('mmt.created', 'DESC')
            ->execute()
            ->fetchAllAssoc('id');
    }

    /**
     * Update a record.
     */
    public function updateRecord(int $id, array $fields): int {
        return $this->database->update('my_module_table')
            ->fields($fields)
            ->condition('id', $id)
            ->execute();
    }

    /**
     * Delete a record.
     */
    public function deleteRecord(int $id): int {
        return $this->database->delete('my_module_table')
            ->condition('id', $id)
            ->execute();
    }

    /**
     * Raw query with placeholders (if needed).
     */
    public function rawQuery(string $email): ?array {
        return $this->database->query(
            'SELECT * FROM {my_module_table} WHERE email = :email',
            [':email' => $email]
        )->fetchAssoc();
    }
}
```

**Database Schema Definition** (`my_module.install`):

```php
<?php

declare(strict_types=1);

/**
 * Implements hook_schema().
 */
function my_module_schema(): array {
    $schema['my_module_table'] = [
        'description' => 'Stores custom data.',
        'fields' => [
            'id' => [
                'type' => 'serial',
                'not null' => TRUE,
                'description' => 'Primary key.',
            ],
            'email' => [
                'type' => 'varchar',
                'length' => 255,
                'not null' => TRUE,
                'description' => 'User email.',
            ],
            'created' => [
                'type' => 'int',
                'not null' => TRUE,
                'default' => 0,
                'description' => 'Creation timestamp.',
            ],
        ],
        'primary key' => ['id'],
        'indexes' => [
            'email' => ['email'],
            'created' => ['created'],
        ],
    ];

    return $schema;
}
```

---

## 9. Templating with Twig

### Template Structure

Keep templates logic-free. Use preprocessing for data preparation.

```twig
{# templates/my-template.html.twig #}

<div class="my-module-container">
    <h1>{{ title }}</h1>

    {% if items %}
        <ul class="my-module-list">
            {% for item in items %}
                <li class="my-module-item">
                    {{ item.name }}
                </li>
            {% endfor %}
        </ul>
    {% else %}
        <p>{{ empty_message }}</p>
    {% endif %}
</div>
```

### Template Preprocessing

Use `MODULE_preprocess_HOOK()` to prepare variables.

```php
/**
 * Implements hook_preprocess_HOOK() for my-template.
 */
function my_module_preprocess_my_template(array &$variables): void {
    // Load and prepare data
    $variables['title'] = 'My Title';

    $variables['items'] = [
        ['name' => 'Item 1'],
        ['name' => 'Item 2'],
    ];

    $variables['empty_message'] = t('No items found');

    // Attach libraries
    $variables['#attached']['library'][] = 'my_module/my_library';
}
```

### Rendering Arrays

Use render arrays in controllers:

```php
public function page(): array {
    return [
        '#theme' => 'my_template',
        '#title' => 'My Title',
        '#items' => $this->getItems(),
        '#attached' => [
            'library' => ['my_module/my_library'],
        ],
    ];
}
```

---

## 10. Plugins

### Block Plugin

```php
<?php

declare(strict_types=1);

namespace Drupal\my_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\my_module\Service\DataProcessor;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides a custom block.
 *
 * @Block(
 *   id = "my_module_custom_block",
 *   admin_label = @Translation("My Custom Block"),
 * )
 */
class CustomBlock extends BlockBase implements ContainerFactoryPluginInterface {

    public function __construct(
        array $configuration,
        string $plugin_id,
        array $plugin_definition,
        private DataProcessor $dataProcessor
    ) {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {
        return new self(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('my_module.data_processor')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function build(): array {
        $data = $this->dataProcessor->processData([]);

        return [
            '#theme' => 'my_block_template',
            '#data' => $data,
        ];
    }
}
```

---

## 11. Security Best Practices

### Input Validation & Sanitization

```php
// Validate
$email = $form_state->getValue('email');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $form_state->setErrorByName('email', t('Invalid email'));
}

// Sanitize strings
$safe_text = strip_tags($user_input);
$safe_html = \Drupal\Component\Utility\Xss::filter($html_input);

// Database escaping
$query->condition('email', $email);  // Automatically escaped
```

### Output Escaping

```php
// In PHP (avoid in Twig - auto-escaped)
echo Html::escape($variable);
echo Markup::create($safe_html);

// In Twig (automatic)
{{ variable }}              {# Escaped #}
{{ safe_html|raw }}        {# Not escaped - use carefully #}
{{ variable|striptags }}   {# Strip HTML tags #}
```

### Permission Checking

```php
// Check permission in route
requirements:
  _permission: 'access content'

// Check in code
if (!$this->currentUser()->hasPermission('my permission')) {
    throw new AccessDeniedException();
}
```

---

## 12. Testing

### Unit Tests

```php
<?php

declare(strict_types=1);

namespace Drupal\Tests\my_module\Unit\Service;

use Drupal\Tests\UnitTestCase;
use Drupal\my_module\Service\DataProcessor;

/**
 * Tests for DataProcessor service.
 *
 * @covers \Drupal\my_module\Service\DataProcessor
 * @group my_module
 */
class DataProcessorTest extends UnitTestCase {

    private DataProcessor $dataProcessor;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void {
        parent::setUp();

        // Mock dependencies
        $this->dataProcessor = new DataProcessor();
    }

    /**
     * Tests processData method.
     */
    public function testProcessDataSuccess(): void {
        $input = ['key' => 'value'];
        $result = $this->dataProcessor->processData($input);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('processed', $result);
    }

    /**
     * Tests processData with invalid input.
     */
    public function testProcessDataInvalid(): void {
        $this->expectException(\InvalidArgumentException::class);

        $this->dataProcessor->processData([]);
    }
}
```

### Kernel Tests

```php
<?php

declare(strict_types=1);

namespace Drupal\Tests\my_module\Kernel\Service;

use Drupal\KernelTests\KernelTestBase;

/**
 * Kernel tests for database operations.
 *
 * @group my_module
 */
class DatabaseServiceKernelTest extends KernelTestBase {

    protected static array $modules = ['my_module', 'node', 'user'];

    /**
     * Tests database query.
     */
    public function testDatabaseQuery(): void {
        $service = $this->container->get('my_module.database_service');

        // Insert test data
        $service->insertRecord(['email' => 'test@example.com']);

        // Query and verify
        $records = $service->getRecords();
        $this->assertCount(1, $records);
    }
}
```

### Running Tests

```bash
# Run all tests
vendor/bin/phpunit -c phpunit.xml

# Run specific test
vendor/bin/phpunit tests/src/Unit/Service/DataProcessorTest.php

# Run with coverage
vendor/bin/phpunit --coverage-html coverage/ -c phpunit.xml
```

---

## 13. Configuration Management

### Configuration Files

Store site configuration in YAML files in the `config/` directory.

```yaml
# config/sync/my_module.settings.yml
my_module:
  api_key: 'your_key'
  timeout: 30
  enabled_features:
    - feature_1
    - feature_2
```

### Using Configuration in Code

```php
$config = \Drupal::config('my_module.settings');
$api_key = $config->get('my_module.api_key');
$timeout = $config->get('my_module.timeout');

// Or via service
public function __construct(
    private ConfigFactoryInterface $configFactory
) {}

$config = $this->configFactory->get('my_module.settings');
```

---

## 14. Caching

### Cache Tags

```php
// Set cache with tags
$data = ['item' => 'value'];
\Drupal::cache('default')->set('my_key', $data, CacheBackendInterface::CACHE_PERMANENT, ['my_module_tag']);

// Get cached data
$cache = \Drupal::cache('default')->get('my_key');
if ($cache) {
    $data = $cache->data;
}

// Invalidate cache
\Drupal::cache('default')->invalidateTags(['my_module_tag']);
```

### Cache in Render Arrays

```php
return [
    '#cache' => [
        'max-age' => 3600,
        'tags' => ['my_module_tag'],
        'contexts' => ['user'],
    ],
];
```

---

## 15. Logging & Debugging

### Using Logger Service

```php
$this->logger->emergency('Emergency message');
$this->logger->alert('Alert message');
$this->logger->critical('Critical message');
$this->logger->error('Error message');
$this->logger->warning('Warning message');
$this->logger->notice('Notice message');
$this->logger->info('Info message');
$this->logger->debug('Debug message');

// With placeholders
$this->logger->info('User @uid performed action', ['@uid' => $uid]);
```

Register logger in services:

```yaml
services:
  my_module.logger:
    parent: logger.channel_base
    arguments: ['my_module']
```

### Debugging

- Use `dsm()` or `dpm()` (requires Devel module)
- Use `dump()` for quick debugging
- Check Drupal logs at `/admin/reports/dblog`

---

## 16. Common Patterns & Best Practices

### Singleton Pattern (Anti-pattern)
❌ Don't use singletons. Use the service container instead.

### Factory Pattern
✅ Use for creating complex objects.

```php
services:
  my_module.factory:
    class: Drupal\my_module\Factory\MyFactory
    arguments:
      - '@service1'
      - '@service2'
```

### Observer Pattern
✅ Use event subscribers for event handling.

### Builder Pattern
✅ Use for complex object construction in forms and entities.

### Repository Pattern
✅ Use services to manage data access.

---

## 17. Performance Tips

### 1. Use Lazy Loading
- Load entities and data only when needed
- Use `$entity->load()` instead of full preload

### 2. Batch Processing
- Use Batch API for processing large datasets
- Process items in chunks to avoid timeout

### 3. Caching
- Cache expensive operations
- Use appropriate cache tags and contexts

### 4. Database Indexing
- Index frequently queried fields
- Use indexes in `hook_schema()`

### 5. Queries
- Select only needed fields
- Use `->range()` for pagination

### 6. Asset Aggregation
- Enable CSS/JS aggregation in production
- Use libraries and dependency declaration

---

## 18. Quick Reference Checklist

### Before Creating a Module
- [ ] Module purpose clearly defined
- [ ] Follows Drupal naming conventions
- [ ] All dependencies declared in `.info.yml`
- [ ] Module info file complete and accurate

### Code Quality
- [ ] PHP 8.3 syntax (typed properties, union types)
- [ ] `declare(strict_types=1);` in all files
- [ ] PHPDoc blocks on all classes and methods
- [ ] Dependency injection used throughout
- [ ] No `\Drupal::` static calls in classes

### Architecture
- [ ] Business logic in services
- [ ] Controllers lightweight
- [ ] Hooks in dedicated files or services
- [ ] Forms use FormBase classes
- [ ] Plugins use proper base classes

### Security
- [ ] Input validated and sanitized
- [ ] Output escaped (auto-escaped in Twig)
- [ ] User permissions checked
- [ ] Database queries use prepared statements
- [ ] No hardcoded sensitive data

### Testing
- [ ] Unit tests for services
- [ ] Kernel tests for database operations
- [ ] Tests use proper setup/teardown
- [ ] Coverage above 80%

### Performance
- [ ] Caching implemented where appropriate
- [ ] Queries optimized
- [ ] Assets aggregated
- [ ] No N+1 query problems

### Documentation
- [ ] Code is well-commented
- [ ] README explaining module purpose
- [ ] API documentation in PHPDoc
- [ ] Examples in docblocks

---

## 19. Resources & References

- [Drupal Coding Standards](https://www.drupal.org/docs/develop/standards)
- [Drupal 10 API](https://www.drupal.org/docs/drupal-apis)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Twig Documentation](https://twig.symfony.com/doc/)
- [Symfony DI Container](https://symfony.com/doc/current/service_container.html)

---

## 20. Getting Help

- **Drupal.org**: https://www.drupal.org
- **Drupal Slack**: https://drupal.slack.com
- **Stack Overflow**: Tag with `drupal`
- **Drupal Issue Queue**: Report bugs and feature requests

