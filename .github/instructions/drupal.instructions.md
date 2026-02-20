# Drupal Project Instructions

## Purpose
Use this file for all Drupal 10 and above contributions in this repository. Follow Drupal best practices, keep code modular and testable, and prefer core APIs over custom implementations.

## Platform
- Drupal Core: 10.x and above
- PHP: 8.4

## Coding Standards
- Follow Drupal Coding Standards and the repository `phpcs.xml` rules.
- Prefer the Drupal core reference configs when applicable: `drupal/core/phpcs.xml.dist`, `drupal/core/phpunit.xml.dist`, and `drupal/core/phpstan.neon.dist`.
- Every PHP file must start with `declare(strict_types=1);`.
- Use typed properties and return types for all methods and functions.
- Add PHPDoc for all classes, methods, and functions with clear descriptions and `@param`, `@return`, `@throws` as needed.
- Keep files and classes small and single-responsibility.

## Architecture
- Use dependency injection for all services. Do not call `\Drupal::service()` or `\Drupal::database()`.
- Define services in `*.services.yml` and inject them via constructors.
- Business logic belongs in services; controllers should only orchestrate requests and responses.
- Prefer core APIs: Entity API, Form API, Database API, Config API, Cache API, and Logger API.

## Composer and Dependencies
- Do not edit `vendor/` directly.

## Routing, Controllers, and Forms
- Use routing YAML with `_permission` or `_access` for access control.
- Controllers should be thin and return render arrays or responses.
- Use Form API for forms and validation; keep validation reusable in services when possible.

## Entities, Config, and State
- Use the Entity API for content and configuration entities.
- Store site configuration in Config API, not in code or custom tables.
- Use State API only for runtime or environment-specific values.
- Provide default configuration in `config/install` when needed.
- Use `config/schema` to define configuration schemas.
- Prefer configuration synchronization for deployable changes.

## Hooks, Events, and Plugins
- Prefer plugins when extending Drupal behavior (field types, blocks, views, etc.).
- Use hooks for integration points when a plugin is not available.
- Keep hook implementations thin; delegate to services.

## Database and Migrations
- Use Database API with injected `@database` service.
- Avoid raw SQL unless absolutely required; document why if used.
- Prefer Entity API or Migrate API for imports and migrations.

## Caching and Performance
- Use Cache API with appropriate cache tags, contexts, and max-age.
- Add cache metadata to render arrays, not Twig.
- Invalidate cache via tags when content changes.
- Avoid heavy work in request lifecycle; consider queues or cron for batch tasks.

## Security
- Sanitize all input and validate user data.
- Use Twig auto-escaping and Drupal render API for output.
- Enforce permissions via routing and `$currentUser->hasPermission()`.
- Avoid rendering user input without proper filtering.
- Use `#allowed_tags` and text formats where appropriate.

## Logging and Errors
- Use injected logger services and meaningful channel names.
- Do not expose sensitive details in error messages or logs.

## Testing
- Use PHPUnit. Place tests in `tests/src/` for the module.
- Add unit and kernel tests for service logic.
- Prefer testable, injected dependencies over static calls.

## Documentation
- Update `README.md` or module docs when adding features or configuration steps.
- Document any non-obvious behavior or operational constraints.

