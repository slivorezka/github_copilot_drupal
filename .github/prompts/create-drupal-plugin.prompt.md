# Prompt: Create a Drupal Plugin Class

## Purpose
Generate a well-structured Drupal plugin class following best practices, plugin architecture patterns, and PHP 8.3 standards.

## Instructions

You are a Drupal 10 expert. Create a plugin class for `{PLUGIN_TYPE}` with the following requirements:

### Requirements

**Plugin Details:**
- **Plugin Type**: {PLUGIN_TYPE} (e.g., Block, Field, Views, Search API, Condition)
- **Plugin ID**: {PLUGIN_ID}
- **Module**: {MODULE_NAME}
- **Purpose**: {PLUGIN_PURPOSE}
- **Base Class**: {BASE_CLASS} (e.g., BlockPluginBase, FieldWidgetBase)
- **Dependencies**: {LIST_DEPENDENCIES} (optional)

**Class Structure:**
1. **Strict Types**: Include `declare(strict_types=1);` at the very start
2. **Annotation**: Include proper plugin annotation with:
   - `@Plugin`
   - `id = "{plugin_id}"`
   - `admin_label = @Translation("{Human Readable Label}")`
   - Other type-specific annotations
3. **Namespace**: Follow Drupal convention `Drupal\{module_name}\Plugin\{PluginType}\{PluginClassName}`
4. **Methods**: Implement required abstract methods and custom methods:
   - `build()` or `buildConfigurationForm()` (if applicable)
   - {CUSTOM_METHOD_1}: {METHOD_DESCRIPTION_1}
   - {CUSTOM_METHOD_2}: {METHOD_DESCRIPTION_2}

**Code Quality:**
- Add comprehensive PHPDoc blocks for class and all methods
- Use typed properties and return types for all methods
- Use typed parameters with proper type hints
- Include `@param`, `@return`, `@throws` in PHPDoc
- Use camelCase for method/property names
- Use UPPERCASE_WITH_UNDERSCORES for constants
- Use meaningful variable names
- Add comments for complex logic

**Best Practices:**
- Implement all required abstract methods from base class
- Use configuration arrays properly
- Handle dependencies via constructor or create() method
- Follow plugin-specific interface requirements
- Include form validation if applicable
- Provide defaults for optional configuration
- Add comprehensive examples in docblocks

**File Structure:**
```php
<?php

declare(strict_types=1);

namespace Drupal\{module_name}\Plugin\{PluginType};

use Drupal\Core\Plugin\PluginBase;
use Drupal\Component\Annotation\Plugin;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * {Human Readable Plugin Name} plugin.
 *
 * Detailed description of what this plugin does.
 *
 * @Plugin(
 *   id = "{plugin_id}",
 *   admin_label = @Translation("{Human Readable Label}"),
 *   category = @Translation("{Category}"),
 * )
 */
class {PluginClassName} extends {BaseClass} {

    /**
     * Constructor.
     *
     * {@inheritdoc}
     */
    public function __construct(
        array $configuration,
        string $plugin_id,
        mixed $plugin_definition,
        // Additional dependencies if needed
    ) {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {
        return new self($configuration, $plugin_id, $plugin_definition);
    }

    /**
     * Method description.
     *
     * @return [ReturnType]
     *   Description of return value.
     */
    public function methodName(): [ReturnType] {
        // Implementation
    }
}
```

**Plugin Annotation Details:**
- `@Plugin`: Main plugin annotation
- `id`: Machine name of the plugin (unique)
- `admin_label`: Human-readable name (translatable)
- `category`: Plugin category (translatable)
- Type-specific annotations (varies by plugin type)

### Output Requirements

1. **Complete PHP Plugin Class File** with:
   - Opening PHP tag with strict types
   - Proper namespace
   - All required imports
   - Proper annotation with @Plugin
   - Complete class with all required methods
   - Comprehensive PHPDoc documentation

2. **Plugin Discovery Notes** - How Drupal discovers this plugin

3. **Usage Example** - Code showing how the plugin is used

4. **Configuration Example** (if applicable) - Default configuration

5. **Key Features** - Brief explanation of plugin capabilities

---

## Example: Block Plugin

### Input:
```
Plugin Type: Block
Plugin ID: search_feature_block
Module: my_module
Purpose: Display a custom search feature block with filtering options
Base Class: BlockPluginBase
Methods:
  - build(): Render the block content
  - blockAccess(): Check access permissions
```

### Output:

```php
<?php

declare(strict_types=1);

namespace Drupal\my_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Component\Annotation\Plugin;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Search feature block plugin.
 *
 * Displays a custom search interface with advanced filtering options.
 * This block allows users to search content with multiple filter criteria
 * including date range, content type, and author.
 *
 * @Block(
 *   id = "search_feature_block",
 *   admin_label = @Translation("Search Feature Block"),
 *   category = @Translation("Search"),
 * )
 *
 * @see \Drupal\my_module\Service\SearchService
 * @ingroup block_plugins
 */
class SearchFeatureBlock extends BlockBase implements ContainerFactoryPluginInterface {

    /**
     * The current user.
     *
     * @var \Drupal\Core\Session\AccountProxyInterface
     */
    protected AccountProxyInterface $currentUser;

    /**
     * Constructor.
     *
     * @param array $configuration
     *   A configuration array containing information about the plugin instance.
     * @param string $plugin_id
     *   The plugin_id for the plugin instance.
     * @param mixed $plugin_definition
     *   The plugin implementation definition.
     * @param \Drupal\Core\Session\AccountProxyInterface $current_user
     *   The current user service.
     */
    public function __construct(
        array $configuration,
        string $plugin_id,
        mixed $plugin_definition,
        AccountProxyInterface $current_user,
    ) {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->currentUser = $current_user;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(
        ContainerInterface $container,
        array $configuration,
        $plugin_id,
        $plugin_definition,
    ): self {
        return new self(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('current_user'),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function build(): array {
        $config = $this->getConfiguration();

        return [
            '#theme' => 'search_feature_block',
            '#title' => $config['title'] ?? $this->t('Search'),
            '#show_filters' => $config['show_filters'] ?? TRUE,
            '#allow_advanced' => $config['allow_advanced'] ?? FALSE,
            '#attached' => [
                'library' => ['my_module/search_block'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function blockAccess(AccountInterface $account): AccessResultInterface {
        // Allow access if user has permission
        return AccessResult::allowedIfHasPermission($account, 'access content');
    }

    /**
     * {@inheritdoc}
     */
    public function defaultConfiguration(): array {
        return [
            'title' => $this->t('Search Content'),
            'show_filters' => TRUE,
            'allow_advanced' => FALSE,
            'default_type' => 'all',
            'results_per_page' => 10,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function blockForm($form, FormStateInterface $form_state): array {
        $config = $this->getConfiguration();

        $form['title'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Block Title'),
            '#default_value' => $config['title'],
            '#required' => TRUE,
        ];

        $form['show_filters'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Show Filter Options'),
            '#default_value' => $config['show_filters'],
        ];

        $form['allow_advanced'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Allow Advanced Search'),
            '#default_value' => $config['allow_advanced'],
        ];

        $form['results_per_page'] = [
            '#type' => 'number',
            '#title' => $this->t('Results Per Page'),
            '#default_value' => $config['results_per_page'],
            '#min' => 1,
            '#max' => 100,
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function blockSubmit($form, FormStateInterface $form_state): void {
        $this->configuration['title'] = $form_state->getValue('title');
        $this->configuration['show_filters'] = $form_state->getValue('show_filters');
        $this->configuration['allow_advanced'] = $form_state->getValue('allow_advanced');
        $this->configuration['results_per_page'] = $form_state->getValue('results_per_page');
    }
}
```

**Plugin Discovery:**
- Drupal automatically discovers plugins in `/src/Plugin/{PluginType}/` directory
- Filename must match class name: `SearchFeatureBlock.php`
- Plugin ID is defined in `@Plugin` annotation
- Can be managed via UI at `/admin/structure/block/manage/{block_id}`

**Usage in Module:**
- Place block in block layout: `/admin/structure/block`
- Configure settings in block configuration form
- Theme in template: `search-feature-block.html.twig`

**Template Example** (`search-feature-block.html.twig`):
```twig
<div class="search-feature-block">
    {% if title %}
        <h2 class="search-feature-block__title">{{ title }}</h2>
    {% endif %}

    <form class="search-feature-block__form" method="GET" action="/search">
        <input
            type="text"
            name="q"
            class="search-feature-block__input"
            placeholder="Enter search terms..."
            required
        >

        {% if show_filters %}
            <div class="search-feature-block__filters">
                <label>
                    Content Type:
                    <select name="type">
                        <option value="">-- All --</option>
                        <option value="article">Article</option>
                        <option value="page">Page</option>
                    </select>
                </label>
            </div>
        {% endif %}

        <button type="submit" class="search-feature-block__submit">
            {{ 'Search'|t }}
        </button>
    </form>
</div>
```

---

## Plugin Type Examples

### Block Plugin
```php
@Block(
   id = "my_block",
   admin_label = @Translation("My Block"),
   category = @Translation("Custom"),
)
class MyBlock extends BlockBase implements ContainerFactoryPluginInterface {
    // Implement build(), defaultConfiguration(), blockForm(), blockSubmit()
}
```

### Field Widget Plugin
```php
@FieldWidget(
   id = "my_widget",
   module = "my_module",
   label = @Translation("My Widget"),
   field_types = {
     "text",
     "text_long",
   }
)
class MyWidget extends WidgetBase {
    // Implement formElement(), massageFormValues()
}
```

### Views Filter Plugin
```php
@ViewsFilter(
   id = "my_filter",
   title = @Translation("My Filter"),
)
class MyFilter extends FilterPluginBase {
    // Implement query(), buildOptionsForm(), validateOptionsForm()
}
```

### Search API Processor Plugin
```php
@SearchApiProcessor(
   id = "my_processor",
   label = @Translation("My Processor"),
   description = @Translation("Process search data"),
   stages = {
     "pre_index_save" = 0,
     "post_index_save" = 0,
   }
)
class MyProcessor extends ProcessorPluginBase {
    // Implement preIndexSave(), postIndexSave()
}
```

### Condition Plugin
```php
@Condition(
   id = "my_condition",
   label = @Translation("My Condition"),
   category = @Translation("Custom"),
)
class MyCondition extends ConditionPluginBase {
    // Implement evaluate(), buildConfigurationForm()
}
```

---

## Common Plugin Patterns

### Plugin with Configuration Form

```php
public function defaultConfiguration(): array {
    return [
        'label' => '',
        'enabled' => TRUE,
        'max_items' => 10,
    ];
}

public function blockForm($form, FormStateInterface $form_state): array {
    $config = $this->getConfiguration();

    $form['label'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Label'),
        '#default_value' => $config['label'],
    ];

    $form['enabled'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Enabled'),
        '#default_value' => $config['enabled'],
    ];

    $form['max_items'] = [
        '#type' => 'number',
        '#title' => $this->t('Maximum Items'),
        '#default_value' => $config['max_items'],
        '#min' => 1,
    ];

    return $form;
}

public function blockSubmit($form, FormStateInterface $form_state): void {
    $this->configuration['label'] = $form_state->getValue('label');
    $this->configuration['enabled'] = $form_state->getValue('enabled');
    $this->configuration['max_items'] = $form_state->getValue('max_items');
}
```

### Plugin with Dependency Injection

```php
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MyPlugin extends PluginBase implements ContainerFactoryPluginInterface {

    protected MyService $myService;

    public function __construct(
        array $configuration,
        string $plugin_id,
        mixed $plugin_definition,
        MyService $my_service,
    ) {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->myService = $my_service;
    }

    public static function create(
        ContainerInterface $container,
        array $configuration,
        $plugin_id,
        $plugin_definition,
    ): self {
        return new self(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('my_module.my_service'),
        );
    }
}
```

### Plugin with Validation

```php
public function blockValidate($form, FormStateInterface $form_state): void {
    $value = $form_state->getValue('max_items');

    if ($value < 1 || $value > 100) {
        $form_state->setErrorByName('max_items',
            $this->t('Value must be between 1 and 100'));
    }
}
```

---

## Annotation Reference

### @Plugin Annotation
```php
@Plugin(
    id = "unique_plugin_id",
    label = @Translation("Human Readable Label"),
    description = @Translation("Plugin description"),
    category = @Translation("Plugin Category"),
    admin_label = @Translation("Admin Label"),
    ...type-specific options
)
```

### Common Annotation Options
- `id` (required): Unique plugin identifier
- `label`: Human-readable label
- `admin_label`: Label for administrative UI
- `category`: Plugin category
- `description`: Detailed description
- `deriver`: Class for dynamic plugin generation
- `context`: Contextual requirements

### Type-Specific Annotations
- **@Block**: `category`, `admin_label`
- **@FieldWidget**: `field_types`, `module`, `label`
- **@ViewsFilter**: `title`, `real name`
- **@SearchApiProcessor**: `stages`, `description`
- **@Condition**: `label`, `category`, `context`

---

## Customization Tips

### For Block Plugins:
- Implement `blockAccess()` for permission checks
- Use `defaultConfiguration()` for settings
- Implement `blockForm()` and `blockSubmit()` for admin UI
- Add caching via `getCacheContexts()`, `getCacheTags()`, `getCacheMaxAge()`

### For Field Widgets:
- Implement `formElement()` for widget rendering
- Implement `massageFormValues()` for data processing
- Validate user input in `formElement()`
- Use `#options` for select/checkbox widgets

### For Views Plugins:
- Implement `query()` to modify query
- Use `buildOptionsForm()` for settings
- Implement `validateOptionsForm()` for validation
- Add caching metadata

### For Condition Plugins:
- Implement `evaluate()` to return boolean
- Implement `defaultConfiguration()` for defaults
- Implement `buildConfigurationForm()` for admin UI
- Use context for dynamic evaluation

---

## File Location & Naming

Plugin files must be organized correctly for Drupal discovery:

```
modules/custom/my_module/
├── src/
│   └── Plugin/
│       ├── Block/
│       │   └── MyBlock.php
│       ├── Field/
│       │   ├── FieldType/
│       │   ├── FieldWidget/
│       │   └── FieldFormatter/
│       ├── views/
│       │   ├── filter/
│       │   ├── field/
│       │   └── argument/
│       ├── SearchApi/
│       │   └── Processor/
│       └── Condition/
│           └── MyCondition.php
```

---

## Validation Checklist

- [ ] Strict types declared
- [ ] Proper namespace used
- [ ] Correct base class extended
- [ ] @Plugin annotation present and correct
- [ ] All required methods implemented
- [ ] Constructor with proper parameters
- [ ] create() method for dependency injection (if needed)
- [ ] All methods have return types
- [ ] All parameters have types
- [ ] PHPDoc on class and methods
- [ ] @param, @return, @throws tags
- [ ] Configuration form implemented (if applicable)
- [ ] Validation logic included
- [ ] Error handling implemented
- [ ] File in correct directory structure
- [ ] Class name matches filename
- [ ] Plugin ID is unique
- [ ] Translatable strings use t() or @Translation
- [ ] Caching considered (if applicable)
- [ ] Usage example provided

