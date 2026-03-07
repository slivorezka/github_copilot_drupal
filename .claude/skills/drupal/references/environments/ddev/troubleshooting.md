## DDEV-Specific Troubleshooting

### Common DDEV Issues
```bash
# DDEV won't start
ddev poweroff && ddev start

# Port conflicts
# Edit .ddev/config.yaml to change ports
router_http_port: "8080"
router_https_port: "8443"

# Memory issues
# Increase PHP memory in .ddev/php/php.ini
memory_limit = 512M

# Composer memory issues
ddev exec php -d memory_limit=-1 /usr/local/bin/composer install

# Database connection issues
ddev describe    # Check environment status
ddev exec drush sql:connect  # Test database connection
```

### Performance Issues in DDEV
```bash
# Identify slow queries
ddev exec drush sql:query "SELECT * FROM watchdog WHERE type = 'php' ORDER BY wid DESC LIMIT 10"

# Check cache settings
ddev exec drush config:get system.performance

# Enable performance modules
ddev exec drush pm:enable memcache redis -y
```

### Module/Theme Development Issues in DDEV
```bash
ddev exec drush cr

# Service not found
ddev exec drush config:get core.extension

# Twig template not loading
ddev exec drush cr

# Cron issues
ddev exec drush cron
ddev exec drush watchdog:show --type=cron
```

### Testing Issues in DDEV
```bash
# PHPUnit configuration problems
# Ensure phpunit.xml.dist exists and is configured
cp web/core/phpunit.xml.dist phpunit.xml

# Database setup for testing
# Edit phpunit.xml for SIMPLETEST_DB and SIMPLETEST_BASE_URL
SIMPLETEST_DB=mysql://db:db@db/db_test
SIMPLETEST_BASE_URL=http://my-drupal-project.ddev.site

# Browser tests failing
# Install Selenium or ChromeDriver
# Ensure test environment variables are set
```

### DDEV Documentation
- **DDEV Official Docs**: https://ddev.readthedocs.io
- **DDEV Quick Start**: https://ddev.readthedocs.io/en/stable/users/quickstart/
- **DDEV Drupal Guide**: https://ddev.readthedocs.io/en/stable/users/topics/drupal/
