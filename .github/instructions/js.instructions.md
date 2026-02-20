# JavaScript Instructions

## Purpose
Use these rules for all JavaScript in this Drupal 10 repository to keep behavior consistent, secure, and maintainable.

## Standards
- Follow Drupal JavaScript coding standards and project lint rules.
- Keep code modular and scoped to the feature or component.
- Avoid global variables and side effects.
- Prefer modern, readable ES2022 syntax supported by Drupal core tooling.

## Drupal Behaviors
- Use Drupal Behaviors so scripts run on initial page load and after AJAX updates.
- Use `once()` to avoid duplicate bindings.
- Keep behavior functions small; delegate complex logic to helper modules.
- Name behaviors descriptively and avoid anonymous behavior keys.

## Core Patterns
- Use `Drupal.t()` for client-side translations.
- Use `drupalSettings` for server-provided values.
- Prefer `core/drupal` and `core/once` dependencies in `*.libraries.yml` when needed.

## AJAX and API Usage
- Use Drupal AJAX API for server interactions.
- Use Drupal-provided settings via `drupalSettings`, not hardcoded globals.
- Handle errors gracefully and surface user-friendly messages.

## Asset Management
- Define all JS in `*.libraries.yml` and attach with `{{ attach_library('module/library') }}`.
- Keep libraries minimal; load only what the page needs.
- Prefer ES modules when supported by your build pipeline; avoid bundling unless required.
- Keep scripts colocated with the component or module that owns them.

## Security
- Never inject unsanitized HTML; prefer safe render arrays on the server.
- Validate and sanitize any data used in client-side templates.

## Performance
- Avoid heavy work in behavior attach; defer or split large tasks.
- Prefer event delegation for dynamic content.
- Avoid layout thrashing; batch DOM reads/writes.

## Accessibility
- Preserve expected keyboard interactions and ARIA attributes.
- Ensure dynamic updates announce changes when appropriate.

## Testing
- Add tests for non-trivial behavior using the project’s preferred JS test setup.
- Keep logic testable by isolating pure functions from DOM bindings.
- Add regression coverage for behaviors that interact with AJAX.
