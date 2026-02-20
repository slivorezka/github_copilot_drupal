# PHP Instructions

## Purpose
Use these rules for all PHP in this Drupal 10 and above repository. Follow Drupal Coding Standards, keep code testable, and prefer core APIs.

## Language and Runtime
- PHP: 8.3
- Drupal Core: 10.x and above

## File Structure and Declarations
- Start every PHP file with `declare(strict_types=1);`.
- Use one class per file and match PSR-4 paths.
- Use `final` classes by default; only remove `final` when extension is required.

## Typing and Signatures
- Use typed properties and return types for all methods and functions.
- Prefer strict, narrow types; use nullable types only when required.
- Avoid mixed; use value objects or DTOs for complex data.

## Drupal Architecture
- Use dependency injection for all services; do not call `\Drupal::service()` or `\Drupal::database()`.
- Define services in `*.services.yml` and inject via constructors.
- Keep controllers thin; put business logic in services.
- Prefer core APIs: Entity, Form, Config, Cache, Logger, and Database APIs.

## Coding Standards and Style
- Follow Drupal Coding Standards and the repository `phpcs.xml` rules.
- Write clear, intention-revealing code; keep methods short and focused.
- Avoid global state and side effects; favor pure methods when possible.

## Error Handling and Logging
- Throw specific exceptions; document with `@throws`.
- Use injected logger services with meaningful channels.
- Do not expose sensitive data in exceptions or logs.

## Security
- Validate and sanitize all input.
- Use render arrays and Twig auto-escaping for output.
- Enforce permissions via routing and `$currentUser->hasPermission()`.

## Database and Storage
- Use the Database API with injected `@database` service.
- Avoid raw SQL; if unavoidable, document and parameterize.
- Use Config API for configuration and State API only for runtime values.

## Testing
- Use PHPUnit. Add unit and kernel tests for service logic.
- Place tests in `tests/src/` within the module.
- Keep code testable with injected dependencies and minimal static calls.

## Documentation
- Add PHPDoc for all classes, methods, and functions.
- Use `@param`, `@return`, and `@throws` with clear descriptions.
- Update module docs or `README.md` when behavior changes.
