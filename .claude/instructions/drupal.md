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

```yaml
services:
  my_module.service_name:
    class: Drupal\my_module\Service\MyService
    arguments:
      - '@database'
      - '@entity_type.manager'
```

#### `module_name.routing.yml`

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

```yaml
'my custom permission':
  title: 'My Custom Permission'
  description: 'Description of the permission'
  restrict access: false
```

#### `module_name.libraries.yml`

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

```php
<?php

declare(strict_types=1);

/**
 * Implements hook_ENTITY_TYPE_insert() for nodes.
 */
function my_module_node_insert(EntityInterface $entity): void {
    // React to node creation.
}

/**
 * Implements hook_form_FORM_ID_alter().
 */
function my_module_form_node_form_alter(array &$form, array &$form_state): void {
    // Modify node form.
}

/**
 * Implements hook_preprocess_HOOK().
 */
function my_module_preprocess_node(array &$variables): void {
    // Prepare variables for node template.
}
```

### Event Subscribers

Use event subscribers instead of hooks when possible.

```php
<?php

declare(strict_types=1);

namespace Drupal\my_module\Event;

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
        // Handle event.
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

    /**
     * Constructor.
     */
    public function __construct(
        private readonly MyService $myService,
    ) {}

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container): self {
        return new self(
            $container->get('my_module.service'),
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

---

## 5. Services & Dependency Injection

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
            $result = [];
            return $result;
        }
        catch (\Exception $e) {
            $this->logger->error('Error: @message', ['@message' => $e->getMessage()]);
            throw $e;
        }
    }

}
```

Register in `my_module.services.yml`:

```yaml
services:
  my_module.data_processor:
    class: Drupal\my_module\Service\DataProcessor
    arguments:
      - '@database'
      - '@entity_type.manager'
      - '@logger.channel.my_module'
```

---

## 6. Forms & Form Handling

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

    /**
     * Constructor.
     */
    public function __construct(
        private readonly DataProcessor $dataProcessor,
    ) {}

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container): self {
        return new self(
            $container->get('my_module.data_processor'),
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

---

## 7. Entities & Entity API

```php
<?php

declare(strict_types=1);

namespace Drupal\my_module\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Service for entity operations.
 */
class EntityService {

    /**
     * Constructor.
     */
    public function __construct(
        private readonly EntityTypeManagerInterface $entityTypeManager,
    ) {}

    /**
     * Loads a node by ID.
     */
    public function getNode(int $nid): ?object {
        return $this->entityTypeManager
            ->getStorage('node')
            ->load($nid);
    }

    /**
     * Loads multiple nodes.
     */
    public function getNodes(array $nids): array {
        return $this->entityTypeManager
            ->getStorage('node')
            ->loadMultiple($nids);
    }

    /**
     * Creates and saves a new node.
     */
    public function createNode(array $values): object {
        $node = $this->entityTypeManager
            ->getStorage('node')
            ->create($values);
        $node->save();
        return $node;
    }

    /**
     * Queries entities.
     */
    public function queryNodes(string $type): array {
        $query = $this->entityTypeManager
            ->getStorage('node')
            ->getQuery();

        return $query
            ->condition('type', $type)
            ->condition('status', 1)
            ->sort('created', 'DESC')
            ->accessCheck(TRUE)
            ->execute();
    }

}
```

---

## 8. Database & Queries

Always use prepared statements to prevent SQL injection.

```php
<?php

declare(strict_types=1);

namespace Drupal\my_module\Service;

use Drupal\Core\Database\Connection;

/**
 * Service for database operations.
 */
class DatabaseService {

    /**
     * Constructor.
     */
    public function __construct(
        private readonly Connection $database,
    ) {}

    /**
     * Inserts a record.
     */
    public function insertRecord(array $fields): int {
        return $this->database->insert('my_module_table')
            ->fields($fields)
            ->execute();
    }

    /**
     * Selects records.
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
     * Updates a record.
     */
    public function updateRecord(int $id, array $fields): int {
        return $this->database->update('my_module_table')
            ->fields($fields)
            ->condition('id', $id)
            ->execute();
    }

    /**
     * Deletes a record.
     */
    public function deleteRecord(int $id): int {
        return $this->database->delete('my_module_table')
            ->condition('id', $id)
            ->execute();
    }

}
```

---

## 9. Plugins

### Block Plugin

```php
<?php

declare(strict_types=1);

namespace Drupal\my_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\my_module\Service\DataProcessor;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a custom block.
 *
 * @Block(
 *   id = "my_module_custom_block",
 *   admin_label = @Translation("My Custom Block"),
 * )
 */
class CustomBlock extends BlockBase implements ContainerFactoryPluginInterface {

    /**
     * Constructor.
     */
    public function __construct(
        array $configuration,
        string $plugin_id,
        array $plugin_definition,
        private readonly DataProcessor $dataProcessor,
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
            $container->get('my_module.data_processor'),
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

## 10. Security Best Practices

### Input Validation & Sanitization

```php
// Validate.
$email = $form_state->getValue('email');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $form_state->setErrorByName('email', t('Invalid email'));
}

// Sanitize strings.
$safe_html = \Drupal\Component\Utility\Xss::filter($html_input);

// Database escaping (automatic with query builder).
$query->condition('email', $email);
```

### Output Escaping

```php
// In PHP.
echo Html::escape($variable);

// In Twig (automatic).
{{ variable }}              {# Escaped #}
{{ safe_html|raw }}        {# Not escaped — use carefully #}
```

### Permission Checking

```yaml
# In routing.
requirements:
  _permission: 'access content'
```

```php
// In code.
if (!$this->currentUser()->hasPermission('my permission')) {
    throw new AccessDeniedException();
}
```

---

## 11. Testing

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
        $this->dataProcessor = new DataProcessor();
    }

    /**
     * Tests processData method.
     */
    public function testProcessDataSuccess(): void {
        $input = ['key' => 'value'];
        $result = $this->dataProcessor->processData($input);
        $this->assertIsArray($result);
    }

}
```

### Running Tests

```bash
vendor/bin/phpunit -c phpunit.xml
vendor/bin/phpunit tests/src/Unit/Service/DataProcessorTest.php
```

---

## 12. Caching

```php
// Set cache with tags.
\Drupal::cache('default')->set('my_key', $data, CacheBackendInterface::CACHE_PERMANENT, ['my_module_tag']);

// Invalidate cache.
\Drupal::cache('default')->invalidateTags(['my_module_tag']);

// Cache in render arrays.
return [
    '#cache' => [
        'max-age' => 3600,
        'tags' => ['my_module_tag'],
        'contexts' => ['user'],
    ],
];
```

---

## 13. Logging

```php
$this->logger->info('User @uid performed action', ['@uid' => $uid]);
$this->logger->error('Error: @message', ['@message' => $e->getMessage()]);
```

Register logger in services:

```yaml
services:
  my_module.logger:
    parent: logger.channel_base
    arguments: ['my_module']
```

---

## 14. Configuration Management

```php
// Read config via service.
$config = $this->configFactory->get('my_module.settings');
$value = $config->get('my_module.api_key');

// Write config.
$this->configFactory->getEditable('my_module.settings')
    ->set('my_module.api_key', $newValue)
    ->save();
```

---

## 15. Performance Tips

1. **Lazy Loading**: Load entities only when needed
2. **Batch Processing**: Use Batch API for large datasets
3. **Caching**: Cache expensive operations with proper tags/contexts
4. **Database Indexing**: Index frequently queried fields in `hook_schema()`
5. **Select Specific Fields**: Use `->fields('alias', ['field1', 'field2'])` instead of `->fields('alias')`
6. **Batch Entity Loading**: Use `loadMultiple()` instead of `load()` in loops
7. **Asset Aggregation**: Enable CSS/JS aggregation in production

---

## 16. Quick Reference Checklist

### Before Creating a Module
- [ ] Module purpose clearly defined
- [ ] Follows Drupal naming conventions
- [ ] All dependencies declared in `.info.yml`

### Code Quality
- [ ] PHP 8.3 syntax (typed properties, union types)
- [ ] `declare(strict_types=1);` in all files
- [ ] PHPDoc blocks on all classes and methods
- [ ] Dependency injection used throughout
- [ ] No `\Drupal::` static calls in classes

### Architecture
- [ ] Business logic in services
- [ ] Controllers lightweight
- [ ] Forms use FormBase classes
- [ ] Plugins use proper base classes

### Security
- [ ] Input validated and sanitized
- [ ] Output escaped (auto-escaped in Twig)
- [ ] User permissions checked
- [ ] Database queries use prepared statements

### Testing
- [ ] Unit tests for services
- [ ] Kernel tests for database operations
- [ ] Coverage above 80%

