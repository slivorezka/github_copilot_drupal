# Twig Instructions

## Purpose
Use these rules for all Twig templates in this Drupal 10 repository. Keep templates simple, secure, and presentation-focused.

## Standards
- Follow Drupal Twig coding standards and project lint rules.
- Keep templates logic-light; move complex logic to preprocess functions.
- Use clear, semantic markup and consistent class naming (BEM).

## Variables and Preprocess
- Prepare all data in `hook_preprocess_*()` or theme preprocess classes.
- Avoid calling services or APIs directly in Twig.
- Document template variables in PHPDoc blocks in preprocess files.
- Preserve core variables like `attributes`, `title_prefix`, and `title_suffix` when overriding templates.

## Drupal Twig APIs
- Use `path()` and `url()` for routes, and `link()` for links.
- Use `attributes.addClass()` and `without()` to manage classes and attributes safely.
- Use `clean_class` and `clean_id` filters for CSS classes and IDs.
- Prefer `file_url()` for file URIs.

## Translation
- Translate strings with `|t` or `{% trans %}`.
- Do not concatenate translated strings; pass placeholders instead.

## Rendering and Security
- Rely on Twig auto-escaping; never mark unsafe content as raw.
- Use render arrays and filters like `|escape` when needed.
- Do not output user input without proper sanitization on the server.

## Asset Attachment
- Attach assets with `{{ attach_library('module/library') }}`.
- Keep libraries minimal and scoped to the template’s needs.

## Components and Reuse
- Use includes and macros for reusable UI patterns.
- Prefer component-based templates for maintainability.
- Avoid duplicating markup across templates.

## Performance
- Avoid heavy loops or complex conditionals in Twig.
- Use `|render` only when required and document why.
- Cache render arrays with proper cache tags, contexts, and max-age.

## Template Overrides
- Follow Drupal naming conventions for template suggestions.
- Keep overrides minimal; extend or include base templates when possible.
- Verify required libraries are attached when overriding core templates.

## Accessibility
- Ensure semantic HTML, labels, and ARIA attributes when appropriate.
- Maintain proper heading order and accessible navigation landmarks.
