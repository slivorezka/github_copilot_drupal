# PHP 8.3 Coding Standards for Drupal 10

## 1. File Structure & Declarations

### Strict Types Declaration

Every PHP file **must** start with `declare(strict_types=1);`:

```php
<?php

declare(strict_types=1);

namespace Drupal\my_module;
```

### File Headers

Add a file-level PHPDoc block after the `declare` statement for non-trivial files.

---

## 2. Naming Conventions

### Classes & Namespaces
- **PascalCase** for class names: `MyServiceClass`
- Namespace follows Drupal convention: `Drupal\[module_name]\[SubNamespace]`
- Singular names for service classes: `UserService` not `UsersService`

### Methods & Functions
- **camelCase** for methods: `getUserById()`, `processData()`
- **lowercase_with_underscores** for hook implementations: `mymodule_form_alter()`

### Constants & Variables
- **UPPERCASE_WITH_UNDERSCORES** for class constants: `const DEFAULT_TIMEOUT = 30;`
- **camelCase** for variables: `$userId`, `$isActive`

---

## 3. Type Declarations

### Return Types

Always declare return types for all methods and functions:

```php
public function getUserById(int $id): ?User {
    // ...
}

public function validateEmail(string $email): bool {
    // ...
}

public function processItems(array $items): void {
    // ...
}
```

### Parameter Types

Always declare parameter types. Avoid `mixed` unless absolutely necessary:

```php
private function processData(string $input, int $count): void {
    // ...
}
```

### Property Types

Always declare types for class properties:

```php
private string $name;
protected int $count = 0;
public bool $isActive;
```

### Constructor Promotion

Use PHP 8.x constructor promotion with `private readonly`:

```php
public function __construct(
    private readonly Connection $database,
    private readonly EntityTypeManagerInterface $entityTypeManager,
    private readonly LoggerInterface $logger,
) {}
```

---

## 4. PHPDoc Blocks

### Class Documentation

```php
/**
 * Provides user management functionality.
 *
 * This service handles user creation, deletion, and updates.
 */
class UserService {
    // ...
}
```

### Method Documentation

```php
/**
 * Retrieves a user by their ID.
 *
 * @param int $userId
 *   The user ID to retrieve.
 *
 * @return \Drupal\user\Entity\User|null
 *   The user object, or NULL if not found.
 *
 * @throws \Drupal\Core\Entity\EntityStorageException
 *   If the entity load fails.
 */
public function getUser(int $userId): ?User {
    // ...
}
```

### Function Documentation

```php
/**
 * Implements hook_ENTITY_TYPE_insert().
 *
 * @param \Drupal\Core\Entity\EntityInterface $entity
 *   The entity object being inserted.
 */
function mymodule_node_insert(EntityInterface $entity): void {
    // ...
}
```

---

## 5. Dependency Injection

### Service Classes

Always use constructor injection. Never use `\Drupal::service()` in classes:

```php
/**
 * Service for managing user data.
 */
class UserService {

    /**
     * Constructor.
     *
     * @param \Drupal\Core\Database\Connection $database
     *   The database service.
     * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
     *   The entity type manager service.
     */
    public function __construct(
        private readonly Connection $database,
        private readonly EntityTypeManagerInterface $entityTypeManager,
    ) {}

}
```

### Registering Services

```yaml
services:
  mymodule.user_service:
    class: Drupal\mymodule\Service\UserService
    arguments:
      - '@database'
      - '@entity_type.manager'
```

---

## 6. Controllers

Controllers should only validate input, call services, and return responses:

```php
/**
 * Controller for user operations.
 */
class UserController extends ControllerBase {

    /**
     * Constructor.
     */
    public function __construct(
        private readonly UserService $userService,
    ) {}

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container): self {
        return new self(
            $container->get('mymodule.user_service'),
        );
    }

    /**
     * Returns user data as JSON.
     */
    public function getUser(int $userId): JsonResponse {
        try {
            $user = $this->userService->getUser($userId);
            return new JsonResponse($user);
        }
        catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

}
```

---

## 7. Drupal APIs

### Database Queries

Use the Database API via dependency injection with prepared statements:

```php
// ❌ WRONG
$result = \Drupal::database()->query("SELECT * FROM users WHERE id = $id");

// ✅ CORRECT
$result = $this->database->query(
    'SELECT * FROM {users} WHERE id = :id',
    [':id' => $id]
);
```

### Entity Management

```php
$user = $this->entityTypeManager
    ->getStorage('user')
    ->load($userId);
```

---

## 8. Error Handling

### Exceptions

Throw specific exception types. Document them in PHPDoc:

```php
/**
 * @throws \Drupal\Core\Entity\EntityStorageException
 * @throws \InvalidArgumentException
 */
public function processUser(int $userId): void {
    if ($userId <= 0) {
        throw new \InvalidArgumentException('User ID must be positive');
    }
}
```

### Logging

```php
$this->logger->info('User @id processed', ['@id' => $userId]);
$this->logger->error('Failed to process user @id', ['@id' => $userId]);
```

---

## 9. Code Style & Formatting

### Spacing & Indentation
- **2 spaces** for indentation (Drupal standard)
- Blank lines between methods
- Lines under 120 characters

### Arrays
- Short array syntax `[]` (not `array()`)
- Trailing commas in multiline arrays:

```php
$config = [
    'id' => 'user_1',
    'name' => 'John Doe',
    'email' => 'john@example.com',
];
```

### Control Structures
- Use `elseif` (not `else if`)
- Always use braces, even for single-line blocks
- Avoid deeply nested conditions (max 3–4 levels)

```php
if ($isActive) {
    doSomething();
}
elseif ($isPending) {
    doOtherThing();
}
else {
    doDefault();
}
```

---

## 10. Security Best Practices

### Input Sanitization

Always sanitize user input. Never trust `$_GET`, `$_POST`, or raw form values:

```php
$safe_html = \Drupal\Component\Utility\Xss::filter($html_input);
```

### Output Escaping

```php
echo Html::escape($variable);
```

### Permissions

```php
if (!$this->currentUser->hasPermission('manage users')) {
    throw new AccessDeniedException();
}
```

---

## 11. Testing

### Test Structure
- Unit tests in `tests/src/Unit/`
- Kernel tests in `tests/src/Kernel/`
- Test classes: `[ClassName]Test` (e.g., `UserServiceTest`)
- Test methods: `test[MethodName][Scenario]` (e.g., `testGetUserByIdNotFound`)

### Test Coverage

Aim for 80%+ on services and business logic:

```php
/**
 * @covers \Drupal\mymodule\Service\UserService
 */
class UserServiceTest extends UnitTestCase {

    /**
     * Tests successful user retrieval.
     */
    public function testGetUserByIdSuccess(): void {
        // Arrange, Act, Assert.
    }

    /**
     * Tests user not found scenario.
     */
    public function testGetUserByIdNotFound(): void {
        // Arrange, Act, Assert.
    }

}
```

---

## 12. Quick Reference Checklist

- [ ] File starts with `declare(strict_types=1);`
- [ ] All classes, methods, properties have type declarations
- [ ] All public/protected methods have PHPDoc blocks
- [ ] Classes use constructor dependency injection
- [ ] No `\Drupal::service()` calls in classes
- [ ] Business logic in services, not controllers
- [ ] Database queries use prepared statements
- [ ] Proper error handling with specific exceptions
- [ ] User permissions checked for sensitive operations
- [ ] Code follows Drupal Coding Standards
- [ ] Tests written for new business logic

