## Testing & Quality Assurance

### PHPUnit Testing Framework
Aim for ≥ 80% code coverage. Drupal provides multiple test types:

```bash
# Run all tests with coverage
vendor/bin/phpunit -v --coverage-html coverage/

# Run specific test suites
vendor/bin/phpunit --testsuite unit          # Unit tests (fast)
vendor/bin/phpunit --testsuite kernel         # Kernel tests
vendor/bin/phpunit --testsuite functional     # Functional tests (slower)
vendor/bin/phpunit --testsuite javascript     # JavaScript tests

# Run specific tests
vendor/bin/phpunit --filter MyModuleUnitTest
vendor/bin/phpunit modules/custom/my_module/tests/src/Unit/

# Run with custom configuration
SIMPLETEST_DB=sqlite://localhost/tmp.sqlite vendor/bin/phpunit
```

### Test Types and Examples

#### Unit Tests (fastest)
- **Purpose**: Test individual classes and methods in isolation
- **Base class**: Extend `UnitTestCase` from `Drupal\Tests\UnitTestCase`
- **Speed**: Fastest test type, no Drupal bootstrap required
- **Isolation**: Test one piece of functionality at a time
- **Dependencies**: Mock external dependencies and services
- **Location**: Place in `tests/src/Unit/` directory
- **Use cases**: Service logic calculations, utility functions, data transformations
- **Best practices**: Keep tests small, focused, and deterministic

#### Kernel Tests (with database)
- **Purpose**: Test Drupal interactions with minimal Drupal environment
- **Base class**: Extend `KernelTestBase` from `Drupal\KernelTests\KernelTestBase`
- **Environment**: Partial Drupal bootstrap with in-memory database
- **Modules**: Declare required modules in `$modules` static property
- **Database**: Uses SQLite in-memory database for speed
- **Location**: Place in `tests/src/Kernel/` directory
- **Use cases**: Entity CRUD operations, configuration validation, service registration
- **Setup**: Install modules and configuration in `setUp()` method

#### Functional Tests (with browser)
- **Purpose**: Test complete user interactions through browser simulation
- **Base class**: Extend `BrowserTestBase` from `Drupal\Tests\BrowserTestBase`
- **Environment**: Full Drupal bootstrap with real browser
- **Speed**: Slowest test type, full page loads required
- **Theme**: Set `$defaultTheme` property (usually 'stark' or 'claro')
- **Location**: Place in `tests/src/Functional/` directory
- **Use cases**: Form submissions, page access, user permissions, JavaScript interactions
- **Browser simulation**: Uses Goutte/ChromeDriver for browser automation
- **Assertions**: Use `$this->assertSession()` for web assertions

### Code Quality Tools
```bash
# Static analysis (add to composer require)
vendor/bin/phpstan analyse                      # PHPStan analysis
vendor/bin/psalm                               # Psalm analysis

# Security scanning
vendor/bin/drupal-check                        # Check for deprecated code
composer audit                                 # Check for security advisories

# Accessibility testing
vendor/bin/phpunit --group accessibility       # Accessibility tests
```

### JavaScript Testing
```bash
# Install JavaScript dependencies
npm install

# Run JavaScript tests
npm run test                                   # Jest tests
npm run test:a11y                             # Accessibility tests
```

### Before Submitting Code
```bash
# Quality checklist
vendor/bin/phpcs --standard=Drupal .          # Code style
vendor/bin/phpunit                             # Run tests
drush cr                                       # Clear caches
drush updatedb                                 # Run updates
```
