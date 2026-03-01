You are a Drupal 10 expert. Create a well-structured Drupal plugin class following best practices, plugin architecture patterns, and PHP 8.3 standards.

Parse the following input for plugin details: $ARGUMENTS

The input should contain: plugin type (Block, Field, Views, Condition, etc.), plugin ID, module name, purpose, and any custom methods. If details are missing, infer reasonable defaults.

## Requirements

### Class Structure
1. Start with `declare(strict_types=1);`
2. Namespace: `Drupal\{module_name}\Plugin\{PluginType}\{PluginClassName}`
3. Include proper `@Plugin` annotation (`@Block`, `@FieldWidget`, etc.) with:
   - `id = "{plugin_id}"`
   - `admin_label = @Translation("{Human Readable Label}")`
   - Other type-specific annotations
4. Extend the correct base class (`BlockBase`, `WidgetBase`, etc.)
5. Implement `ContainerFactoryPluginInterface` if dependencies are needed
6. Use `create()` method for dependency injection

### Plugin Types Reference

**Block Plugin:**
```php
@Block(id = "my_block", admin_label = @Translation("My Block"), category = @Translation("Custom"))
```
Methods: `build()`, `blockAccess()`, `defaultConfiguration()`, `blockForm()`, `blockSubmit()`

**Field Widget Plugin:**
```php
@FieldWidget(id = "my_widget", module = "my_module", label = @Translation("My Widget"), field_types = {"text"})
```
Methods: `formElement()`, `massageFormValues()`

**Condition Plugin:**
```php
@Condition(id = "my_condition", label = @Translation("My Condition"))
```
Methods: `evaluate()`, `buildConfigurationForm()`

### Code Quality
- Comprehensive PHPDoc blocks for class and all methods
- Include `@param`, `@return`, `@throws` tags
- Use typed properties and return types throughout
- Implement all required abstract methods from base class
- Include configuration form with validation if applicable
- Use translatable strings with `$this->t()` or `@Translation`

### Output

Generate all of the following:

1. **Complete PHP Plugin Class** — Full file with annotation, constructor, `create()` method, and all required methods.

2. **Template File** (if applicable) — Twig template for the plugin output with proper BEM naming and documentation.

3. **Plugin Discovery Notes** — How Drupal discovers this plugin (directory structure, annotation).

4. **Usage Example** — How to use/configure the plugin.

### File Location

Plugin files must be in the correct directory for Drupal discovery:
```
src/Plugin/Block/MyBlock.php
src/Plugin/Field/FieldWidget/MyWidget.php
src/Plugin/Condition/MyCondition.php
```

### Validation Checklist
- [ ] Strict types declared
- [ ] Proper namespace and directory
- [ ] Correct base class extended
- [ ] `@Plugin` annotation present and correct
- [ ] All required abstract methods implemented
- [ ] `create()` method for DI (if needed)
- [ ] All methods have return types
- [ ] PHPDoc on class and all methods
- [ ] Configuration form with validation (if applicable)
- [ ] Plugin ID is unique
- [ ] Translatable strings use `$this->t()` or `@Translation`

