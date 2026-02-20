# Comprehensive Drupal Twig Instructions

## 1. Twig Fundamentals

### What is Twig?

Twig is Drupal's templating engine. It separates presentation logic from business logic and provides a secure, fast, and flexible way to render HTML.

### Key Principles

- **Logic-free Templates**: Keep business logic in PHP (controllers, services, preprocessing)
- **Auto-escaping**: Output is escaped by default to prevent XSS vulnerabilities
- **Readability**: Twig syntax is clean and easy to understand
- **Reusability**: Create components using include and extends
- **Performance**: Twig templates are compiled to PHP for efficient execution

---

## 2. File Naming & Organization

### Template File Naming

Follow Drupal's naming convention:

```
templates/
├── my-template.html.twig
├── my-template--variant.html.twig
├── components/
│   ├── card/
│   │   └── card.html.twig
│   ├── button/
│   │   └── button.html.twig
│   └── hero/
│       └── hero.html.twig
├── layouts/
│   └── my-layout.html.twig
└── pages/
    └── my-page.html.twig
```

### Naming Convention

- Use **lowercase** with **hyphens** (kebab-case): `my-template.html.twig`
- Use double hyphens for variants: `node--article.html.twig`, `node--article--featured.html.twig`
- Use descriptive names: `card-with-image.html.twig`, not `template1.html.twig`
- Group related templates in subdirectories

### Module Template Registration

Register templates in `my_module.module`:

```php
<?php

declare(strict_types=1);

/**
 * Implements hook_theme().
 */
function my_module_theme(): array {
    return [
        'my_template' => [
            'variables' => [
                'title' => NULL,
                'items' => [],
                'description' => NULL,
            ],
            'template' => 'my-template',
            'path' => drupal_get_path('module', 'my_module') . '/templates',
        ],
    ];
}
```

---

## 3. Template Structure & Best Practices

### Basic Template Structure

```twig
{#
  /**
   * @file
   * Template for displaying a custom component.
   *
   * Available variables:
   * - title (string): The component title.
   * - items (array): List of items to display.
   * - description (string): Component description.
   *
   * @ingroup themeable
   */
#}

<div class="my-component">
  {% if title %}
    <h2 class="my-component__title">{{ title }}</h2>
  {% endif %}

  {% if description %}
    <p class="my-component__description">{{ description }}</p>
  {% endif %}

  {% if items %}
    <ul class="my-component__list">
      {% for item in items %}
        <li class="my-component__item">{{ item }}</li>
      {% endfor %}
    </ul>
  {% else %}
    <p class="my-component__empty">{{ 'No items found'|t }}</p>
  {% endif %}
</div>
```

### File Header Documentation

Always include a documentation block at the top of templates:

```twig
{#
  /**
   * @file
   * Brief description of template.
   *
   * Detailed explanation of what this template is for and when it's used.
   *
   * Available variables:
   * - variable_name (type): Description.
   * - another_variable (type): Description.
   *
   * Available classes/IDs (from preprocess):
   * - css-class: Applied when condition is true.
   *
   * @ingroup themeable
   */
#}
```

---

## 4. Variable Naming & Documentation

### Variable Naming Convention

- Use **camelCase** for variable names: `{{ myVariable }}`, `{{ itemCount }}`
- Use **lowercase_with_underscores** for HTML attributes: `data-item-id`, `aria-label`
- Prefix boolean variables with "is" or "has": `{{ isActive }}`, `{{ hasImage }}`
- Use plural for arrays: `{{ items }}`, `{{ links }}`

### Documenting Variables

Document all available variables in the template header:

```twig
{#
  /**
   * Available variables:
   * - user (object): The current user object.
   *   - uid (int): User ID.
   *   - name (string): Username.
   * - content (array): Entity content.
   * - attributes (Attribute): HTML attributes object.
   * - classes (array): CSS classes array.
   * - url (string): Entity URL.
   * - label (string): Entity label.
   */
#}
```

---

## 5. Output & Escaping

### Auto-escaping (Default)

Output is automatically escaped in Twig to prevent XSS:

```twig
{# Automatically escaped #}
{{ title }}                  {# Safe - HTML special characters escaped #}
{{ user.name }}              {# Safe - nested properties escaped #}
{{ form.field }}             {# Safe - form elements escaped #}
```

### When NOT to Escape

Use the `|raw` filter ONLY when output is guaranteed to be safe:

```twig
{# ⚠️ Use with caution - only for trusted content #}
{{ safe_html|raw }}         {# Content already escaped in PHP #}
{{ content }}               {# Drupal render arrays are safe #}
{{ content.field_body }}    {# Entity render arrays are safe #}
```

### Common Escaping Filters

```twig
{# Strip HTML tags #}
{{ html_content|striptags }}

{# URL encode #}
<a href="{{ url|escape('url') }}">Link</a>

{# HTML encode attributes #}
<div data-title="{{ title|escape('html_attr') }}">

{# Plain text escaping (default) #}
{{ text|escape }}
```

---

## 6. Conditionals & Control Structures

### If Statements

```twig
{# Simple if #}
{% if isActive %}
    <span class="badge badge--active">Active</span>
{% endif %}

{# If/else #}
{% if user.isAuthenticated %}
    <p>Welcome, {{ user.name }}</p>
{% else %}
    <p><a href="/login">Login</a></p>
{% endif %}

{# If/elseif/else #}
{% if status == 'published' %}
    <span class="status-published">Published</span>
{% elseif status == 'draft' %}
    <span class="status-draft">Draft</span>
{% else %}
    <span class="status-archived">Archived</span>
{% endif %}
```

### Logical Operators

```twig
{# AND #}
{% if title and description %}
    Both fields present
{% endif %}

{# OR #}
{% if isPending or isApproved %}
    Status is set
{% endif %}

{# NOT #}
{% if not isEmpty %}
    Has content
{% endif %}

{# Complex conditions #}
{% if (isActive and isPaid) or (isPremium and not isExpired) %}
    User can access
{% endif %}
```

### Null Coalescing

```twig
{# Use value if not null, otherwise use default #}
{{ title ?? 'Untitled' }}

{# Check for multiple conditions #}
{% if item is not null and item is not empty %}
    <p>{{ item }}</p>
{% endif %}
```

---

## 7. Loops & Iteration

### For Loops

```twig
{# Basic loop #}
{% for item in items %}
    <div>{{ item }}</div>
{% endfor %}

{# Loop with key-value pairs #}
{% for key, value in data %}
    <dt>{{ key }}</dt>
    <dd>{{ value }}</dd>
{% endfor %}

{# Loop with range #}
{% for i in 1..10 %}
    <li>Item {{ i }}</li>
{% endfor %}

{# Loop with step #}
{% for i in 0..100..10 %}
    {{ i }}
{% endfor %}
```

### Loop Variables

Twig provides special variables inside loops:

```twig
{% for item in items %}
    {# loop.index: 1-based index #}
    {% if loop.index == 1 %}First item{% endif %}

    {# loop.index0: 0-based index #}
    {{ loop.index0 }}

    {# loop.first: True on first iteration #}
    {% if loop.first %}<ul>{% endif %}

    {# loop.last: True on last iteration #}
    <li>{{ item }}</li>
    {% if loop.last %}</ul>{% endif %}

    {# loop.length: Total items in loop #}
    {{ loop.index }} of {{ loop.length }}

    {# loop.revindex: Reverse index (countdown) #}
    {{ loop.revindex }} items remaining

    {# loop.revindex0: Reverse index 0-based #}
    {{ loop.revindex0 }}

    {# loop.even/odd: Check if iteration is even or odd #}
    {% if loop.even %}<tr class="even">{% else %}<tr class="odd">{% endif %}
{% endfor %}
```

### Empty Loop Handling

```twig
{% for item in items %}
    <li>{{ item }}</li>
{% else %}
    <li>No items found</li>
{% endfor %}
```

---

## 8. Filters

### String Filters

```twig
{# Convert to uppercase #}
{{ text|upper }}

{# Convert to lowercase #}
{{ text|lower }}

{# Capitalize first letter #}
{{ text|capitalize }}

{# Truncate string #}
{{ longText|slice(0, 50)|raw }}...

{# Replace text #}
{{ text|replace({'old': 'new'}) }}

{# Format with sprintf #}
{{ 'Hello %s'|format(username) }}

{# URL encode #}
{{ url|url_encode }}

{# Translate (requires t filter) #}
{{ 'My Label'|t }}
```

### Array Filters

```twig
{# Join array items #}
{{ items|join(', ') }}

{# Get array length #}
{% if items|length > 0 %}

{# Get first/last item #}
{{ items|first }}
{{ items|last }}

{# Merge arrays #}
{{ array1|merge(array2) }}

{# Sort array #}
{% for item in items|sort %}

{# Unique values #}
{{ items|unique }}

{# Reverse #}
{{ items|reverse }}

{# Filter (requires filter function) #}
{{ items|filter(item => item.active) }}

{# Map (requires map function) #}
{{ items|map(item => item.name)|join(', ') }}

{# Batch into chunks #}
{% for batch in items|batch(3) %}
    <div class="row">
        {% for item in batch %}
            <div class="col">{{ item }}</div>
        {% endfor %}
    </div>
{% endfor %}
```

### Number Filters

```twig
{# Format number #}
{{ price|number_format(2, '.', ',') }}

{# Humanize bytes #}
{{ filesize|file_size }}

{# Pluralize #}
{{ count }} item{{ count != 1 ? 's' : '' }}
```

### Date Filters

```twig
{# Format date #}
{{ date|date('Y-m-d') }}
{{ date|date('l, F j, Y') }}

{# Time ago #}
{{ timestamp|date('U') }}

{# Available formats #}
{{ date|date('short') }}      {# 12/31/2025 #}
{{ date|date('medium') }}     {# Dec 31, 2025 #}
{{ date|date('long') }}       {# December 31, 2025 #}
```

---

## 9. Drupal-Specific Filters & Functions

### Translation (t filter)

```twig
{# Translate static text #}
{{ 'Hello World'|t }}

{# Translate with context #}
{{ 'File'|t({}, {'context': 'file-context'}) }}

{# Translate with placeholders #}
{{ 'Hello @name'|t({'@name': user.name}) }}

{# Pluralization #}
{% trans %}
    You have 1 item.
{% plural count %}
    You have @count items.
{% endtrans %}
```

### Safe HTML & Markup

```twig
{# Create safe markup #}
{{ content }}  {# Render arrays are safe #}

{# For HTML content from services (pre-escaped in PHP) #}
{{ html_content|raw }}
```

### Rendering Content

```twig
{# Render entity content #}
{{ content }}

{# Render specific field #}
{{ content.field_image }}

{# Render with custom attributes #}
<div{{ attributes }}>
    {{ content }}
</div>

{# Check if field has content #}
{% if content.field_body %}
    {{ content.field_body }}
{% endif %}
```

### Attributes Object

```twig
{# Render attributes object #}
<div{{ attributes }}>

{# Add classes dynamically #}
<div{{ attributes.addClass('my-class') }}>

{# Remove attributes #}
<div{{ attributes.removeClass('unwanted-class') }}>

{# Set/modify attributes #}
<div{{ attributes.setAttribute('data-id', item.id) }}>
```

### Link Generation

```twig
{# Generate link from URL object #}
{{ link(title, url) }}

{# Generate path #}
<a href="{{ path('route.name', {'id': item.id}) }}">Link</a>

{# Generate URL #}
<a href="{{ url('route.name', {'id': item.id}) }}">Link</a>

{# Generate absolute URL #}
<a href="{{ url('route.name', {'id': item.id}, {'absolute': true}) }}">Link</a>
```

---

## 10. Template Inheritance

### Extending Templates

Create a base template:

```twig
{# templates/base.html.twig #}

<!DOCTYPE html>
<html>
<head>
    <title>{% block title %}My Site{% endblock %}</title>
    {% block head %}{% endblock %}
</head>
<body>
    <header>{% block header %}Header{% endblock %}</header>

    <main>
        {% block content %}{% endblock %}
    </main>

    <footer>{% block footer %}Footer{% endblock %}</footer>
</body>
</html>
```

Extend the base template:

```twig
{# templates/page.html.twig #}

{% extends "base.html.twig" %}

{% block title %}Page Title{% endblock %}

{% block content %}
    <h1>{{ pageTitle }}</h1>
    <p>{{ pageContent }}</p>
{% endblock %}

{% block footer %}
    <p>Custom footer for this page</p>
{% endblock %}
```

### Block Reference

```twig
{# Reference parent block content #}

{% extends "base.html.twig" %}

{% block content %}
    <div class="page-wrapper">
        {{ parent() }}  {# Include parent block content #}
    </div>
{% endblock %}
```

---

## 11. Template Includes

### Include Templates

```twig
{# Include another template #}
{% include 'components/card.html.twig' %}

{# Pass variables to included template #}
{% include 'components/card.html.twig' with {
    'title': item.title,
    'image': item.image,
    'description': item.description
} %}

{# Include without passing current context #}
{% include 'components/card.html.twig' with {
    'title': item.title
} only %}
```

### Dynamic Includes

```twig
{# Include template based on condition #}
{% include 'components/' ~ componentType ~ '.html.twig' %}

{# Include with fallback #}
{% include [
    'components/' ~ componentType ~ '.html.twig',
    'components/default.html.twig'
] %}
```

### Reusable Component Examples

**Button Component** (`components/button.html.twig`):

```twig
{#
  /**
   * @file
   * Button component.
   *
   * Variables:
   * - text (string): Button text.
   * - url (string): Button URL.
   * - type (string): Button type (primary, secondary, danger).
   * - disabled (bool): Is button disabled.
   */
#}

<a href="{{ url }}" class="btn btn--{{ type|default('primary') }}" {% if disabled %}disabled{% endif %}>
    {{ text }}
</a>
```

Usage:

```twig
{% include 'components/button.html.twig' with {
    'text': 'Click me',
    'url': '/submit',
    'type': 'primary'
} %}
```

**Card Component** (`components/card.html.twig`):

```twig
{#
  /**
   * @file
   * Card component.
   *
   * Variables:
   * - title (string): Card title.
   * - image (string): Card image URL.
   * - description (string): Card description.
   * - link (string): Card link URL.
   */
#}

<div class="card">
    {% if image %}
        <img src="{{ image }}" alt="{{ title }}" class="card__image">
    {% endif %}

    <div class="card__content">
        {% if title %}
            <h3 class="card__title">{{ title }}</h3>
        {% endif %}

        {% if description %}
            <p class="card__description">{{ description }}</p>
        {% endif %}

        {% if link %}
            <a href="{{ link }}" class="card__link">Learn More</a>
        {% endif %}
    </div>
</div>
```

---

## 12. Classes & CSS

### BEM (Block Element Modifier) Naming

Use BEM for CSS class naming in templates:

```twig
<div class="card">                              {# Block #}
    <div class="card__header">                 {# Element #}
        <h2 class="card__title">Title</h2>
    </div>

    <div class="card__body">
        <p class="card__description">Desc</p>
    </div>

    <div class="card__footer">
        <button class="card__button card__button--primary">Action</button>
        <button class="card__button card__button--secondary">Cancel</button>
    </div>
</div>
```

### Dynamic Classes

```twig
{# Add classes conditionally #}
<div class="card {% if isFeatured %}card--featured{% endif %}">

{# Multiple conditional classes #}
<div class="card
    {% if isFeatured %}card--featured{% endif %}
    {% if hasImage %}card--with-image{% endif %}
    {% if isSmall %}card--small{% endif %}
">

{# Build class array #}
{% set classes = ['card'] %}
{% if isFeatured %}{% set classes = classes|merge(['card--featured']) %}{% endif %}
{% if hasImage %}{% set classes = classes|merge(['card--with-image']) %}{% endif %}

<div class="{{ classes|join(' ') }}">
```

### Drupal Render Arrays & Classes

```twig
{# In preprocess, add to classes array #}
function my_module_preprocess_node(array &$variables): void {
    $node = $variables['node'];

    $variables['attributes']->addClass('node--' . $node->getType());

    if ($node->isPublished()) {
        $variables['attributes']->addClass('node--published');
    }
}

{# In template #}
<article{{ attributes }}>
    {{ content }}
</article>
```

---

## 13. Comments & Documentation

### Twig Comments

```twig
{# Single line comment - not rendered #}

{#
    Multi-line comment
    for documenting logic
#}

{# TODO: Implement user profile logic #}

{# FIXME: This needs optimization #}
```

### File-level Documentation

```twig
{#
  /**
   * @file
   * Displays a user profile card.
   *
   * This template renders a card showing user information
   * with profile picture, name, and contact details.
   *
   * Available variables:
   * - user (object): User entity with properties:
   *   - uid (int): User ID.
   *   - name (string): Username.
   *   - mail (string): Email address.
   *   - picture (string): Profile picture URL.
   * - isCurrentUser (bool): Is this the logged-in user.
   * - classes (array): Additional CSS classes.
   *
   * @see my_module_preprocess_user_card()
   * @ingroup themeable
   */
#}
```

---

## 14. Macro Components

### Creating Reusable Macros

```twig
{# macros/forms.html.twig #}

{# Input field macro #}
{% macro input(name, label, type = 'text', required = false) %}
    <div class="form-group">
        <label for="{{ name }}" class="form-label">
            {{ label }}
            {% if required %}<span class="required">*</span>{% endif %}
        </label>
        <input
            type="{{ type }}"
            id="{{ name }}"
            name="{{ name }}"
            class="form-input"
            {% if required %}required{% endif %}
        >
    </div>
{% endmacro %}

{# Text area macro #}
{% macro textarea(name, label, rows = 5, required = false) %}
    <div class="form-group">
        <label for="{{ name }}" class="form-label">
            {{ label }}
            {% if required %}<span class="required">*</span>{% endif %}
        </label>
        <textarea
            id="{{ name }}"
            name="{{ name }}"
            rows="{{ rows }}"
            class="form-textarea"
            {% if required %}required{% endif %}
        ></textarea>
    </div>
{% endmacro %}
```

### Using Macros

```twig
{% import 'macros/forms.html.twig' as forms %}

<form method="POST">
    {{ forms.input('email', 'Email Address', 'email', true) }}
    {{ forms.input('name', 'Full Name', 'text', true) }}
    {{ forms.textarea('message', 'Your Message', 10, true) }}

    <button type="submit">Send</button>
</form>
```

---

## 15. Preprocessing & Data Preparation

### Preprocess Function Pattern

Always prepare data in PHP, not in templates:

```php
<?php

declare(strict_types=1);

/**
 * Implements hook_preprocess_HOOK().
 */
function my_module_preprocess_my_component(array &$variables): void {
    $node = $variables['node'] ?? NULL;

    if ($node) {
        // Process data
        $variables['title'] = $node->getTitle();
        $variables['description'] = $node->get('field_description')->value ?? '';

        // Format dates
        $variables['created_date'] = date('Y-m-d', $node->getCreatedTime());

        // Load related entities
        $variables['author'] = $node->getOwner();

        // Add computed values
        $variables['is_published'] = $node->isPublished();

        // Build classes
        $variables['classes'][] = 'component--' . $node->getType();

        if ($node->get('field_featured')->value) {
            $variables['classes'][] = 'component--featured';
        }
    }
}
```

### Complex Data Processing

```php
/**
 * Implements hook_preprocess_HOOK().
 */
function my_module_preprocess_card_grid(array &$variables): void {
    $nodes = $variables['items'] ?? [];
    $processed_items = [];

    foreach ($nodes as $node) {
        // Transform each item
        $processed_items[] = [
            'title' => $node->getTitle(),
            'image' => $node->get('field_image')->uri ?? NULL,
            'description' => substr($node->get('field_body')->value, 0, 100),
            'url' => $node->toUrl()->toString(),
            'date' => $node->getCreatedTime(),
        ];
    }

    $variables['items'] = $processed_items;
}
```

### Caching in Preprocess

```php
/**
 * Implements hook_preprocess_HOOK().
 */
function my_module_preprocess_my_template(array &$variables): void {
    // Add cache tags
    $variables['#cache']['tags'][] = 'node_list';
    $variables['#cache']['tags'][] = 'taxonomy_term_list';

    // Set cache max age (1 hour)
    $variables['#cache']['max-age'] = 3600;

    // Add cache context (varies by user)
    $variables['#cache']['contexts'][] = 'user';
}
```

---

## 16. Drupal Render Arrays in Templates

### Rendering Fields

```twig
{# Full entity render #}
{{ content }}

{# Individual field render #}
{{ content.field_body }}

{# Field without wrapper #}
<div class="my-wrapper">
    {{ content.field_image|render }}
</div>

{# Check field exists and has content #}
{% if content.field_body %}
    <div class="body">
        {{ content.field_body }}
    </div>
{% endif %}

{# Render with attributes #}
<div class="field-wrapper"{{ content_attributes }}>
    {{ content.field_body }}
</div>
```

### Filtering Rendered Content

```php
/**
 * Preprocess to hide/show fields.
 */
function my_module_preprocess_node(array &$variables): void {
    // Hide certain fields by not including in content
    unset($variables['content']['field_admin_only']);

    // Or set display: none via attributes
    $variables['content']['field_secondary']['#attributes']['class'][] = 'hidden';
}
```

---

## 17. Conditional Rendering

### Using Conditions Properly

```twig
{# Simple condition #}
{% if title %}
    <h1>{{ title }}</h1>
{% endif %}

{# Nested conditions #}
{% if user %}
    {% if user.isAdmin %}
        <div class="admin-tools">Admin</div>
    {% endif %}
{% endif %}

{# Check array/object properties #}
{% if item.image.url %}
    <img src="{{ item.image.url }}" alt="{{ item.image.alt }}">
{% endif %}

{# Test filters #}
{% if item|default %}
    <p>{{ item }}</p>
{% endif %}

{# Type checking #}
{% if item is iterable %}
    {% for i in item %}...{% endfor %}
{% endif %}

{% if value is empty %}
    No value
{% endif %}

{% if value is not null %}
    {{ value }}
{% endif %}

{% if string is same as(compareTo) %}
    Exact match
{% endif %}
```

---

## 18. Security Best Practices

### Input Validation in Templates

```twig
{# ✅ SAFE: Auto-escaped output #}
{{ user.name }}                    {# Automatically escaped #}
{{ form.element }}                 {# Form elements safe #}
{{ content }}                      {# Render arrays safe #}

{# ⚠️ CAUTION: Must be pre-escaped in PHP #}
{{ html_content|raw }}             {# Only if escaped in PHP #}

{# ❌ AVOID: Dynamic HTML generation #}
{{ '<span>' ~ title ~ '</span>'|raw }}  {# Never do this! #}

{# ✅ CORRECT: Prepare in PHP #}
{# In preprocess: $variables['wrapped_title'] = '<span>' . $title . '</span>'; #}
{{ wrapped_title|raw }}
```

### XSS Prevention

```twig
{# Never trust user input - always escape #}
{% if user_provided_html %}
    {# This is dangerous! #}
    {{ user_provided_html }}  {# Wrong! #}

    {# Do this instead #}
    {{ user_provided_html|striptags }}  {# Remove HTML #}
    {{ user_provided_html|escape }}     {# Escape HTML #}
{% endif %}
```

### Attribute Escaping

```twig
{# ✅ SAFE: Automatic escaping #}
<div data-value="{{ value }}">

{# Use escape filter for HTML attributes #}
<div data-tooltip="{{ tooltip|escape('html_attr') }}">

{# Use url escape for URLs #}
<a href="{{ url|escape('url') }}">
```

---

## 19. Performance Tips

### Lazy Loading & Conditional Rendering

```twig
{# Don't process if not needed #}
{% if should_show_sidebar %}
    {% include 'sidebar.html.twig' %}
{% endif %}

{# Use loop early exit #}
{% for item in items %}
    {% if loop.index > 10 %}{% break %}{% endif %}
    <li>{{ item }}</li>
{% endfor %}
```

### Reduce Template Complexity

```twig
{# ❌ Complex logic in template #}
{% for item in items %}
    {% if item.status == 'active' and item.user.role == 'admin' and item.type == 'featured' %}
        Do something complex
    {% endif %}
{% endfor %}

{# ✅ Process in PHP #}
{# In preprocess: filter items before passing to template #}
{% for item in activeAdminFeaturedItems %}
    <li>{{ item.title }}</li>
{% endfor %}
```

### Cache Rendering

```php
/**
 * Preprocess with caching.
 */
function my_module_preprocess_expensive_template(array &$variables): void {
    $cache_key = 'my_module:template:expensive_data';

    if ($cache = \Drupal::cache()->get($cache_key)) {
        $variables['data'] = $cache->data;
    } else {
        // Expensive operation
        $variables['data'] = expensiveOperation();

        // Cache for 1 hour with tags
        \Drupal::cache()->set(
            $cache_key,
            $variables['data'],
            CacheBackendInterface::CACHE_PERMANENT,
            ['my_module_tag']
        );
    }
}
```

---

## 20. Common Patterns

### Alert/Notification Component

```twig
{# components/alert.html.twig #}

{#
  /**
   * Alert component.
   *
   * Variables:
   * - message (string): Alert message.
   * - type (string): Alert type (success, warning, error, info).
   * - dismissible (bool): Can be dismissed.
   */
#}

<div class="alert alert--{{ type|default('info') }}" role="alert">
    {% if dismissible %}
        <button type="button" class="alert__close" aria-label="Close">×</button>
    {% endif %}

    <div class="alert__message">
        {{ message }}
    </div>
</div>
```

### Tabs Component

```twig
{# components/tabs.html.twig #}

<div class="tabs">
    <div class="tabs__nav" role="tablist">
        {% for tab in tabs %}
            <button
                class="tabs__button {% if loop.first %}tabs__button--active{% endif %}"
                role="tab"
                aria-selected="{% if loop.first %}true{% else %}false{% endif %}"
                aria-controls="tab-{{ loop.index }}"
            >
                {{ tab.label }}
            </button>
        {% endfor %}
    </div>

    <div class="tabs__content">
        {% for tab in tabs %}
            <div
                class="tabs__panel {% if loop.first %}tabs__panel--active{% endif %}"
                id="tab-{{ loop.index }}"
                role="tabpanel"
            >
                {{ tab.content }}
            </div>
        {% endfor %}
    </div>
</div>
```

### Pagination Component

```twig
{# components/pagination.html.twig #}

{% if pager %}
    <nav class="pagination" aria-label="Pagination">
        <ul class="pagination__list">
            {# Previous link #}
            {% if pager.previous %}
                <li class="pagination__item">
                    <a href="{{ pager.previous }}" class="pagination__link">
                        ← Previous
                    </a>
                </li>
            {% endif %}

            {# Page numbers #}
            {% for page_num, page_url in pager.pages %}
                <li class="pagination__item">
                    {% if page_url %}
                        <a href="{{ page_url }}" class="pagination__link">
                            {{ page_num }}
                        </a>
                    {% else %}
                        <span class="pagination__link pagination__link--active">
                            {{ page_num }}
                        </span>
                    {% endif %}
                </li>
            {% endfor %}

            {# Next link #}
            {% if pager.next %}
                <li class="pagination__item">
                    <a href="{{ pager.next }}" class="pagination__link">
                        Next →
                    </a>
                </li>
            {% endif %}
        </ul>
    </nav>
{% endif %}
```

---

## 21. Debugging Templates

### Dumping Variables

```twig
{# Dump all variables (requires Devel module) #}
{{ dump() }}

{# Dump specific variable #}
{{ dump(myVariable) }}

{# Dump multiple variables #}
{{ dump(var1, var2, var3) }}

{# Check variable type #}
{{ myVar|type }}

{# Print variable as readable JSON #}
<pre>{{ items|json_encode(constant('JSON_PRETTY_PRINT'))|raw }}</pre>
```

### Comments for Debugging

```twig
{# DEBUG: Check if variable exists #}
{% if myVar %}Variable exists{% else %}Missing{% endif %}

{# DEBUG: List all available variables #}
Available vars: {% for key in _context|keys %}{{ key }}{% if not loop.last %}, {% endif %}{% endfor %}
```

---

## 22. Quick Reference Checklist

### Template Quality
- [ ] All templates have file header documentation
- [ ] All variables are documented
- [ ] Output is auto-escaped (only use |raw when safe)
- [ ] No business logic in templates
- [ ] Use semantic HTML
- [ ] Proper BEM class naming

### Performance
- [ ] Data processed in preprocess functions
- [ ] No complex loops or conditionals
- [ ] Proper caching implemented
- [ ] Minimal included templates
- [ ] Lazy load where possible

### Security
- [ ] All user input escaped
- [ ] URLs properly encoded
- [ ] Attributes properly escaped
- [ ] No dynamic HTML generation
- [ ] Form fields properly rendered

### Accessibility
- [ ] Semantic HTML (header, nav, main, footer)
- [ ] Proper heading hierarchy
- [ ] ARIA labels where needed
- [ ] Color not sole indicator
- [ ] Keyboard navigation supported

### Code Style
- [ ] Consistent indentation (2 spaces)
- [ ] Descriptive variable names (camelCase)
- [ ] Proper filter usage
- [ ] No code duplication
- [ ] Follows BEM conventions

---

## 23. Resources & References

- [Twig Official Documentation](https://twig.symfony.com/)
- [Drupal Twig Documentation](https://www.drupal.org/docs/drupal-apis/twig-api)
- [Twig Best Practices](https://twig.symfony.com/doc/3.x/)
- [Drupal Theming Guide](https://www.drupal.org/docs/develop/theming)
- [Accessible Template Patterns](https://www.drupal.org/docs/develop/accessibility)

---

## 24. Common Gotchas

### Variable Undefined Error

```twig
{# ❌ Error if variable undefined #}
{{ myVar }}

{# ✅ Safe with default #}
{{ myVar|default('No value') }}

{# ✅ Check existence first #}
{% if myVar is defined %}
    {{ myVar }}
{% endif %}
```

### Loop Variable Scope

```twig
{# ❌ loop variable only available inside loop #}
{% for item in items %}
    {{ item }}
{% endfor %}
{# loop.index not available here #}

{# ✅ Use set for values needed outside loop #}
{% set itemCount = items|length %}
{% for item in items %}
    {{ item }}
{% endfor %}
Item count: {{ itemCount }}
```

### Render Array Rendering

```twig
{# ❌ Don't modify render arrays in templates #}
{% set content.field_title = 'New Title' %}

{# ✅ Modify in preprocess #}
{# In PHP: $variables['content']['field_title']['#markup'] = 'New Title'; #}
```

---

## 25. Getting Help

- **Drupal.org Forums**: https://www.drupal.org/forum
- **Drupal Slack**: https://drupal.slack.com
- **Stack Overflow**: Tag with `drupal` and `twig`
- **Drupal Issue Queue**: Report bugs
- **Drupal Documentation**: https://www.drupal.org/docs

