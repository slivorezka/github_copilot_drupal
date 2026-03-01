# Project Instructions — Drupal 10

## 1. Project Overview

- **Framework**: Drupal 10.x on a LAMP stack (Linux, Apache, MySQL/MariaDB, PHP)
- **PHP Version**: 8.3
- **Goal**: Adhere strictly to Drupal best practices; maintain clean, modular, and testable code

### Key Principles

- **DRY**: Reuse code through hooks, plugins, and services
- **SOLID**: Single Responsibility, Open/Closed, Liskov Substitution, Interface Segregation, Dependency Inversion
- **Security First**: Sanitize input, escape output, check permissions
- **Testability**: Write code that's easy to test with PHPUnit
- **Drupal Standards**: Follow official [Drupal Coding Standards](https://www.drupal.org/docs/develop/standards)

---

## 2. Coding Standards & Style

- Strictly follow [Drupal Coding Standards](https://www.drupal.org/docs/develop/standards)
- Every PHP file **must** start with `declare(strict_types=1);`
- Use typed properties and return types for all class methods and functions
- Adhere to the rules defined in the project's `phpcs.xml` file
- All classes, methods, and functions must have comprehensive PHPDoc blocks (`@param`, `@return`, `@throws`)

See `@.claude/instructions/php.md` for full PHP coding standards.

---

## 3. Architecture & Backend

- **Dependency Injection**: Always use DI to get services in classes. Never use `\Drupal::service()` or other static accessors in classes.
- **Services**: Define all services in the module's `.services.yml`. Keep services small — Single Responsibility Principle.
- **Controllers**: Lightweight. Handle request, call services, return response. No business logic.
- **Drupal APIs**: Always prefer core APIs (Form API, Entity API, Database API) over custom implementations. Use `@database` service via DI, not `\Drupal::database()`.

See `@.claude/instructions/drupal.md` for comprehensive Drupal development rules.

---

## 4. Frontend Development

- **Twig**: Keep templates logic-free; use preprocess functions to prepare variables. Attach assets with `{{ attach_library('module/library') }}`.
- **JavaScript**: Write JS using Drupal Behaviors with `once()`. Scope to component. Use Drupal AJAX API.
- **CSS**: Use BEM naming, component-based approach. Define all assets in `.libraries.yml`.

See `@.claude/instructions/twig.md` for Twig rules and `@.claude/instructions/js.md` for JavaScript rules.

---

## 5. Testing

- **Framework**: PHPUnit for unit and kernel tests
- **Location**: `tests/src/Unit/` and `tests/src/Kernel/` in each module
- **Coverage**: Aim for 80%+ on custom services and business logic. New features must include tests.

---

## 6. Security

- Sanitize all user input
- Use Twig auto-escaping or Drupal's render API to prevent XSS
- Check permissions via routing (`_permission`) or `$currentUser->hasPermission()`
- Use prepared statements for all database queries

---

## 7. Git & Commit Message Rules

See `@.claude/instructions/git.md` for full Git workflow rules.

### Branch Naming

```
<type>/<ticket-number>-<description>
```

Examples: `feature/329-search-styling`, `bugfix/456-fix-header`, `hotfix/critical-patch`

### Commit Message Format

```
#<TICKET_NUMBER>: <Description>.
```

**Rules:**
- Start with ticket number from branch name (e.g., `#329`)
- Use colon + space after number
- Capitalize first letter of description
- End with period
- Max 72 characters
- Use present tense imperative: "Add", "Fix", "Update", "Remove"
- One logical change per commit

**Examples:**
```
#329: Update search feature styling.
#445: Add user profile page.
#456: Fix header mobile display.
```

---

## 8. Module Structure Reference

```
my_module/
├── src/
│   ├── Controller/
│   ├── Service/
│   ├── Form/
│   ├── Plugin/
│   │   ├── Block/
│   │   ├── Field/
│   │   └── views/
│   ├── Entity/
│   └── Event/
├── templates/
├── css/
├── js/
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

---

## 9. Quick Checklist

- [ ] `declare(strict_types=1);` in all PHP files
- [ ] All classes, methods, properties have type declarations
- [ ] All public/protected methods have PHPDoc blocks
- [ ] Dependency injection used throughout (no `\Drupal::` in classes)
- [ ] Business logic in services, not controllers
- [ ] Database queries use prepared statements
- [ ] Input validated and sanitized
- [ ] Output escaped (auto-escaped in Twig)
- [ ] User permissions checked for sensitive operations
- [ ] Tests written for new business logic
- [ ] Commit messages follow `#<TICKET>: <Description>.` format

