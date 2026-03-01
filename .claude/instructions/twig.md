# Comprehensive Drupal Twig Instructions

## 1. Key Principles

- **Logic-free Templates**: Keep business logic in PHP (controllers, services, preprocess functions)
- **Auto-escaping**: Output is escaped by default to prevent XSS
- **Readability**: Clean, well-documented syntax
- **Reusability**: Create components using include and extends
- **Performance**: Templates are compiled to PHP for efficient execution

---

## 2. File Naming & Organization

### Template File Naming

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
└── layouts/
    └── my-layout.html.twig
```

### Naming Convention

- **Lowercase** with **hyphens** (kebab-case): `my-template.html.twig`
- Double hyphens for variants: `node--article.html.twig`
- Descriptive names: `card-with-image.html.twig`, not `template1.html.twig`

### Template Registration

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
        ],
    ];
}
```

---

## 3. Template Structure

### Basic Template

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

Always include a documentation block:

```twig
{#
  /**
   * @file
   * Brief description of template.
   *
   * Available variables:
   * - variable_name (type): Description.
   *
   * @ingroup themeable
   */
#}
```

---

## 4. Output & Escaping

### Auto-escaping (Default)

```twig
{{ title }}                  {# Safe — HTML special characters escaped #}
{{ user.name }}              {# Safe — nested properties escaped #}
{{ content }}                {# Safe — Drupal render arrays #}
```

### When NOT to Escape

Use `|raw` **only** for trusted content already escaped in PHP:

```twig
{{ safe_html|raw }}          {# Content pre-escaped in PHP #}
{{ content.field_body }}     {# Entity render arrays are safe #}
```

### Common Filters

```twig
{{ html_content|striptags }}
<a href="{{ url|escape('url') }}">Link</a>
<div data-title="{{ title|escape('html_attr') }}">
```

---

## 5. Conditionals

```twig
{% if isActive %}
    <span class="badge badge--active">Active</span>
{% endif %}

{% if user.isAuthenticated %}
    <p>Welcome, {{ user.name }}</p>
{% else %}
    <p><a href="/login">Login</a></p>
{% endif %}

{% if status == 'published' %}
    <span>Published</span>
{% elseif status == 'draft' %}
    <span>Draft</span>
{% else %}
    <span>Archived</span>
{% endif %}

{# Null coalescing #}
{{ title ?? 'Untitled' }}
```

---

## 6. Loops

```twig
{% for item in items %}
    <div>{{ item }}</div>
{% else %}
    <p>No items found</p>
{% endfor %}

{# Loop variables #}
{% for item in items %}
    {% if loop.first %}<ul>{% endif %}
    <li class="{% if loop.even %}even{% else %}odd{% endif %}">
        {{ loop.index }} of {{ loop.length }}: {{ item }}
    </li>
    {% if loop.last %}</ul>{% endif %}
{% endfor %}
```

---

## 7. Drupal-Specific Features

### Translation

```twig
{{ 'Hello World'|t }}
{{ 'Hello @name'|t({'@name': user.name}) }}

{% trans %}
    You have 1 item.
{% plural count %}
    You have @count items.
{% endtrans %}
```

### Attributes Object

```twig
<div{{ attributes }}>
<div{{ attributes.addClass('my-class') }}>
<div{{ attributes.setAttribute('data-id', item.id) }}>
```

### Link Generation

```twig
{{ link(title, url) }}
<a href="{{ path('route.name', {'id': item.id}) }}">Link</a>
<a href="{{ url('route.name', {'id': item.id}) }}">Link</a>
```

### Attach Libraries

```twig
{{ attach_library('my_module/my_library') }}
```

---

## 8. Template Inheritance

### Base Template

```twig
<!DOCTYPE html>
<html>
<head>
    <title>{% block title %}My Site{% endblock %}</title>
</head>
<body>
    <header>{% block header %}{% endblock %}</header>
    <main>{% block content %}{% endblock %}</main>
    <footer>{% block footer %}{% endblock %}</footer>
</body>
</html>
```

### Extending

```twig
{% extends "base.html.twig" %}

{% block title %}Page Title{% endblock %}

{% block content %}
    <h1>{{ pageTitle }}</h1>
{% endblock %}
```

---

## 9. Template Includes

```twig
{% include 'components/card.html.twig' with {
    'title': item.title,
    'image': item.image,
} only %}

{# Dynamic include with fallback #}
{% include [
    'components/' ~ componentType ~ '.html.twig',
    'components/default.html.twig',
] %}
```

---

## 10. BEM CSS Naming

```twig
<div class="card">                              {# Block #}
    <div class="card__header">                   {# Element #}
        <h2 class="card__title">Title</h2>
    </div>
    <div class="card__footer">
        <button class="card__button card__button--primary">Action</button>
    </div>
</div>

{# Dynamic classes #}
{% set classes = ['card'] %}
{% if isFeatured %}{% set classes = classes|merge(['card--featured']) %}{% endif %}
<div class="{{ classes|join(' ') }}">
```

---

## 11. Preprocessing

Always prepare data in PHP, not in templates:

```php
/**
 * Implements hook_preprocess_HOOK().
 */
function my_module_preprocess_my_component(array &$variables): void {
    $node = $variables['node'] ?? NULL;
    if ($node) {
        $variables['title'] = $node->getTitle();
        $variables['is_published'] = $node->isPublished();
        $variables['#attached']['library'][] = 'my_module/my_library';
    }
}
```

---

## 12. Rendering Fields

```twig
{{ content }}
{{ content.field_body }}

{% if content.field_body %}
    <div class="body">{{ content.field_body }}</div>
{% endif %}

<article{{ attributes }}>
    {{ content }}
</article>
```

---

## 13. Security

```twig
{# ✅ SAFE: Auto-escaped #}
{{ user.name }}
{{ form.element }}
{{ content }}

{# ⚠️ CAUTION: Only for pre-escaped content #}
{{ html_content|raw }}

{# ❌ NEVER: Dynamic HTML generation #}
{{ '<span>' ~ title ~ '</span>'|raw }}
```

---

## 14. Performance

```twig
{# Don't process if not needed #}
{% if should_show_sidebar %}
    {% include 'sidebar.html.twig' %}
{% endif %}

{# ❌ Complex logic in template #}
{% for item in items %}
    {% if item.status == 'active' and item.user.role == 'admin' %}
        ...
    {% endif %}
{% endfor %}

{# ✅ Process in PHP preprocess #}
{% for item in filteredItems %}
    <li>{{ item.title }}</li>
{% endfor %}
```

---

## 15. Debugging

```twig
{{ dump() }}
{{ dump(myVariable) }}

{# List available variables #}
{% for key in _context|keys %}{{ key }}{% if not loop.last %}, {% endif %}{% endfor %}
```

---

## 16. Common Gotchas

### Variable Undefined Error

```twig
{# ❌ Error if undefined #}
{{ myVar }}

{# ✅ Safe with default #}
{{ myVar|default('No value') }}

{# ✅ Check existence #}
{% if myVar is defined %}
    {{ myVar }}
{% endif %}
```

### Loop Variable Scope

```twig
{# ❌ loop variable only available inside loop #}
{% for item in items %}{{ item }}{% endfor %}
{# loop.index not available here #}

{# ✅ Use set for values needed outside loop #}
{% set itemCount = items|length %}
```

---

## 17. Quick Reference Checklist

- [ ] All templates have file header documentation
- [ ] All variables are documented
- [ ] Output is auto-escaped (only `|raw` for safe content)
- [ ] No business logic in templates
- [ ] Semantic HTML used
- [ ] BEM class naming
- [ ] Data processed in preprocess functions
- [ ] Proper caching implemented
- [ ] User input escaped
- [ ] ARIA labels where needed
- [ ] Consistent 2-space indentation

