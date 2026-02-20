# GitHub Copilot Instructions for PHP Files in the Drupal Project

## 1. File Structure & Declarations

### Strict Types Declaration
- **Every PHP file MUST start with** `declare(strict_types=1);` as the first line (before namespace or comments).
- This enforces strict type checking and helps catch type-related bugs at runtime.

```php
<?php

declare(strict_types=1);

namespace Drupal\my_module;
```

### File Headers
- Add a file-level PHPDoc block after the `declare` statement for any non-trivial files.
- Include a brief description of the file's purpose.

## 2. Naming Conventions

### Classes & Namespaces
- Use PascalCase for class names (e.g., `MyServiceClass`).
- Namespace structure must follow Drupal conventions: `Drupal\[module_name]\[SubNamespace]`.
- Use singular names for service classes (e.g., `UserService` not `UsersService`).

### Methods & Functions
- Use camelCase for method and function names (e.g., `getUserById()`, `processData()`).
- Use lowercase_with_underscores for hook implementations (e.g., `mymodule_form_alter()`).

### Constants & Variables
- Use UPPERCASE_WITH_UNDERSCORES for class constants (e.g., `const DEFAULT_TIMEOUT = 30;`).
- Use camelCase for variable names (e.g., `$userId`, `$isActive`).
- Prefix private/protected properties with underscore (e.g., `private string $database;`).

## 3. Type Declarations

### Return Types
- **Always declare return types** for all methods and functions.
- Use strict types: `string`, `int`, `bool`, `array`, `void`, or specific class types.
- Use nullable types when appropriate: `?string`, `?int`.
- Use union types for multiple possible types: `string|int`.

```php
public function getUserById(int $id): ?User {
    // ...
}

public function validateEmail(string $email): bool {
    // ...
}
```

### Parameter Types
- **Always declare parameter types** for all method and function parameters.
- Use strict types; avoid generic `mixed` unless absolutely necessary.

```php
private function processData(string $input, int $count): void {
    // ...
}
```

### Property Types
- **Always declare types for class properties** (PHP 7.4+).
- Use access modifiers: `public`, `protected`, `private`.

```php
private string $name;
protected int $count = 0;
public bool $isActive;
```

## 4. PHPDoc Blocks

### Class Documentation
- Every class must have a PHPDoc block describing its purpose.
- Include `@author`, `@package`, or other relevant tags if helpful.

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
- Every public/protected method must have a PHPDoc block.
- Use `@param`, `@return`, `@throws` tags as applicable.
- Provide clear, concise descriptions.

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
- All standalone functions (hooks, utilities) must have PHPDoc blocks.

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

## 5. Dependency Injection

### Service Classes
- **Always use constructor injection** for services.
- Define service dependencies in the module's `services.yml` file.
- **Never use** `\Drupal::service()`, `\Drupal::database()`, or static service accessors in classes.

```php
/**
 * Service for managing user data.
 */
class UserService {

    /**
     * The database service.
     */
    private Database $database;

    /**
     * Constructor.
     *
     * @param \Drupal\Core\Database\Connection $database
     *   The database service.
     */
    public function __construct(Connection $database) {
        $this->database = $database;
    }

    public function getUserById(int $userId): ?array {
        return $this->database->query(...)
            ->fetchAssoc();
    }
}
```

### Registering Services
- Define all services in your module's `MODULE_NAME.services.yml` file.

```yaml
services:
  mymodule.user_service:
    class: Drupal\mymodule\Service\UserService
    arguments:
      - '@database'
```

## 6. Controllers

### Lightweight Controllers
- Controllers should only:
  1. Validate input/permissions
  2. Call services
  3. Return responses
- **Move all business logic to services**.

```php
/**
 * Controller for user operations.
 */
class UserController extends ControllerBase {

    public function __construct(
        private UserService $userService
    ) {}

    public function getUser(int $userId): Response {
        try {
            $user = $this->userService->getUser($userId);
            return new JsonResponse($user);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}
```

## 7. Drupal APIs

### Database Queries
- Use the Database API via dependency injection.
- Always use prepared statements to prevent SQL injection.
- **Never use raw SQL or** `\Drupal::database()` in classes.

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
- Use the Entity API for CRUD operations.
- Load entities via entity storage.

```php
$user = \Drupal::entityTypeManager()
    ->getStorage('user')
    ->load($userId);
```

### Form API
- Keep forms in controllers or as form classes.
- Use `#type`, `#title`, `#required`, etc. for form elements.
- Always validate and sanitize input.

## 8. Error Handling

### Exceptions
- Throw specific exception types (not generic `Exception`).
- Use Drupal's exception classes when appropriate.
- Document thrown exceptions in PHPDoc blocks.

```php
/**
 * @throws \Drupal\Core\Entity\EntityStorageException
 * @throws \InvalidArgumentException
 */
public function processUser(int $userId): void {
    if ($userId <= 0) {
        throw new \InvalidArgumentException('User ID must be positive');
    }
    // ...
}
```

### Logging
- Use the logger service for debugging and error tracking.
- Log at appropriate levels: `debug`, `info`, `warning`, `error`, `critical`.

```php
$this->logger->info('User @id processed', ['@id' => $userId]);
$this->logger->error('Failed to process user @id', ['@id' => $userId]);
```

## 9. Code Style & Formatting

### Spacing & Indentation
- Use 2 spaces for indentation (PSR-12 style in Drupal).
- Add blank lines between methods in classes.
- Keep lines under 120 characters when possible.

### Arrays
- Use short array syntax `[]` instead of `array()`.
- Multiline arrays should have trailing commas.

```php
$config = [
    'id' => 'user_1',
    'name' => 'John Doe',
    'email' => 'john@example.com',
];
```

### Control Structures
- Use `elseif` instead of `else if`.
- Always use braces, even for single-line blocks.
- Avoid deeply nested conditions (max 3-4 levels).

```php
if ($isActive) {
    doSomething();
} elseif ($isPending) {
    doOtherThing();
} else {
    doDefault();
}
```

## 10. Security Best Practices

### Input Sanitization
- Always sanitize user input using Drupal's sanitization functions.
- Never trust `$_GET`, `$_POST`, or form values directly.

### Output Escaping
- Use Twig's auto-escaping in templates.
- Escape output in PHP using appropriate functions.
- Use Drupal's render API when returning markup.

### Permissions
- Always check user permissions before performing sensitive operations.
- Use the `_permission` requirement in routing or check permissions in code.

```php
if (!$this->currentUser->hasPermission('manage users')) {
    throw new AccessDeniedException();
}
```

## 11. Testing

### Test Structure
- Place unit tests in `tests/src/Unit/` directory.
- Place kernel/integration tests in `tests/src/Kernel/` directory.
- Use PHPUnit as the testing framework.

### Test Naming
- Test classes: `[ClassName]Test` (e.g., `UserServiceTest`).
- Test methods: `test[MethodName][Scenario]` (e.g., `testGetUserByIdNotFound`).

### Test Coverage
- Aim for 80%+ code coverage for services and business logic.
- Test happy paths, edge cases, and error scenarios.

```php
/**
 * @covers \Drupal\mymodule\Service\UserService
 */
class UserServiceTest extends UnitTestCase {

    public function testGetUserByIdSuccess(): void {
        // Arrange
        // Act
        // Assert
    }

    public function testGetUserByIdNotFound(): void {
        // Arrange
        // Act
        // Assert
    }
}
```

## 12. Common Patterns

### Singleton/Service Pattern
- Use the service container instead of singletons.
- Register services in `services.yml`.

### Factory Pattern
- Use when creating multiple similar objects.
- Define factories as services.

### Observer Pattern
- Use Drupal's hook system or event subscribers.
- Implement `EventSubscriberInterface` for custom events.

## Quick Reference Checklist

- [ ] File starts with `declare(strict_types=1);`
- [ ] All classes, methods, properties have type declarations
- [ ] All public/protected methods have PHPDoc blocks
- [ ] Classes use constructor dependency injection
- [ ] No `\Drupal::service()` calls in classes
- [ ] Business logic in services, not controllers
- [ ] Database queries use prepared statements
- [ ] Proper error handling with specific exceptions
- [ ] User permissions checked for sensitive operations
- [ ] No hardcoded values; use configuration
- [ ] Code follows Drupal Coding Standards
- [ ] Tests written for new business logic
