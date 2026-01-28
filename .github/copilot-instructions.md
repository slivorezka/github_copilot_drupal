# GitHub Copilot Instructions for the Drupal Project

## 1. Project Overview
- **Technology Stack**: Drupal 10 on a LAMP stack.
- **Key Goal**: Adhere strictly to Drupal best practices and maintain clean, modular, and testable code.

## 2. Language & Versions
- **PHP**: 8.3
- **Drupal Core**: 10.x

## 3. Coding Standards & Style
- **Primary Standard**: Strictly follow [Drupal Coding Standards](https://www.drupal.org/docs/develop/standards).
- **PHP Files**:
  - Every PHP file **must** start with `declare(strict_types=1);`.
  - Use typed properties and return types for all class methods and functions.
  - Adhere to the rules defined in the project's `phpcs.xml` file.
- **Docblocks**:
  - All classes, methods, and functions must have comprehensive PHPDoc blocks.
  - Use `@param`, `@return`, `@throws`, and provide clear descriptions.

## 4. Architecture & Backend
- **Dependency Injection**:
  - Always use dependency injection to get services in classes. Do not use `\Drupal::service()`.
  - Services should be defined in the module's `.services.yml` file.
- **Service Layer**:
  - All business logic must reside in service classes.
  - Services should be small and follow the Single Responsibility Principle.
- **Controllers**:
  - Controllers should be lightweight. Their only role is to handle the request, call the appropriate services, and return a response.
  - Do not put business logic in controllers.
- **Drupal APIs**:
  - Always use Drupal's core APIs (e.g., Form API, Entity API, Database API) instead of custom implementations.
  - For database queries, use the Database API with dependency injection (`@database` service), not `\Drupal::database()`.

## 5. Frontend Development
- **Templating**:
  - Use Twig for all templates.
  - Keep Twig templates logic-free. Use preprocess functions to prepare variables.
  - Use `{{ attach_library('my_module/my_library') }}` to attach CSS/JS.
- **JavaScript**:
  - Write JavaScript using Drupal Behaviors to ensure code runs correctly on initial page load and after AJAX requests.
  - Scope JavaScript to its component to avoid global namespace pollution.
  - Use the Drupal AJAX API for server communication.
- **CSS/Styling**:
  - Use a component-based approach for styling (e.g., BEM).
  - Define styles in Sass (`.scss`) files within component directories.
  - All CSS and JS assets should be defined in a `.libraries.yml` file.

## 6. Testing
- **Framework**: Use PHPUnit for unit and kernel tests.
- **Test Location**: Tests should be placed in the `tests/src/` directory of the corresponding module.
- **Coverage**: Aim for high test coverage for all custom services and business logic. New features should include corresponding tests.

## 7. Security
- **Input**: Sanitize all user input.
- **Output**: Use Twig's auto-escaping or Drupal's render API to prevent XSS vulnerabilities.
- **Permissions**: Check user permissions using the routing system (`_permission`) or the current user service (`$currentUser->hasPermission()`).
