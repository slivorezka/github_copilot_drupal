# JavaScript Instructions for Drupal 10

## Purpose

Rules for all JavaScript in this Drupal 10 repository to keep behavior consistent, secure, and maintainable.

---

## 1. Standards

- Follow Drupal JavaScript coding standards and project lint rules
- Keep code modular and scoped to the feature or component
- Avoid global variables and side effects
- Prefer modern, readable ES2022 syntax supported by Drupal core tooling

---

## 2. Drupal Behaviors

- Use Drupal Behaviors so scripts run on initial page load and after AJAX updates
- Use `once()` to avoid duplicate bindings
- Keep behavior functions small; delegate complex logic to helper modules
- Name behaviors descriptively and avoid anonymous behavior keys

```javascript
(function (Drupal, once) {
  'use strict';

  Drupal.behaviors.myModuleFeature = {
    attach: function (context) {
      once('my-module-feature', '.my-component', context).forEach(function (element) {
        // Initialize component.
      });
    },
    detach: function (context, settings, trigger) {
      // Clean up if needed.
    },
  };
})(Drupal, once);
```

---

## 3. Core Patterns

- Use `Drupal.t()` for client-side translations
- Use `drupalSettings` for server-provided values
- Prefer `core/drupal` and `core/once` dependencies in `*.libraries.yml`
- Never hardcode URLs or configuration values — pass them through `drupalSettings`

---

## 4. AJAX and API Usage

- Use Drupal AJAX API for server interactions
- Use Drupal-provided settings via `drupalSettings`, not hardcoded globals
- Handle errors gracefully and surface user-friendly messages
- Use Drupal AJAX commands (`Drupal.AjaxCommands`) for DOM manipulation

---

## 5. Asset Management

- Define all JS in `*.libraries.yml` and attach with `{{ attach_library('module/library') }}`
- Keep libraries minimal; load only what the page needs
- Prefer ES modules when supported by your build pipeline; avoid bundling unless required
- Keep scripts colocated with the component or module that owns them
- Use `defer` attribute for non-critical scripts

```yaml
my_library:
  js:
    js/my-module.js:
      attributes:
        defer: true
  dependencies:
    - core/drupal
    - core/once
```

---

## 6. Security

- Never inject unsanitized HTML; prefer safe render arrays on the server
- Validate and sanitize any data used in client-side templates
- Avoid `innerHTML` when possible; use DOM APIs or Drupal render system
- Escape user-provided data before inserting into the DOM

---

## 7. Performance

- Avoid heavy work in behavior `attach`; defer or split large tasks
- Prefer event delegation for dynamic content
- Avoid layout thrashing; batch DOM reads/writes
- Use `requestAnimationFrame()` for visual updates
- Debounce/throttle event handlers (scroll, resize, input)

---

## 8. Accessibility

- Preserve expected keyboard interactions and ARIA attributes
- Ensure dynamic updates announce changes when appropriate (use `Drupal.announce()`)
- Don't trap focus; manage focus intentionally on modals/dialogs
- Support screen readers by providing meaningful labels and roles

---

## 9. Testing

- Add tests for non-trivial behavior using the project's preferred JS test setup
- Keep logic testable by isolating pure functions from DOM bindings
- Add regression coverage for behaviors that interact with AJAX
- Test keyboard navigation and screen reader compatibility

---

## 10. Quick Reference Checklist

- [ ] JS wrapped in Drupal Behavior with `once()`
- [ ] No global variables or namespace pollution
- [ ] Uses `Drupal.t()` for translatable strings
- [ ] Uses `drupalSettings` for server values
- [ ] Defined in `*.libraries.yml` with proper dependencies
- [ ] Attached via `{{ attach_library() }}` in Twig
- [ ] No unsanitized HTML injection
- [ ] Event delegation used for dynamic content
- [ ] Accessible (keyboard, ARIA, announcements)
- [ ] Tests written for non-trivial logic

