## DDEV Quick Setup

### Prerequisites
```bash
# Install DDEV (macOS)
brew install ddev/ddev/ddev

# Or download from https://ddev.readthedocs.io/en/stable/users/installation/
# Verify installation
ddev --version
```

### Initialize DDEV Project
```bash
# Clone the repository
git clone <repository-url> my-drupal-project
cd my-drupal-project

# Initialize DDEV configuration
ddev config --project-type=drupal --docroot=web --php-version=8.1

# Start DDEV environment
ddev start

# Install Composer dependencies
ddev composer install

# Install Drupal
# Option 1: Interactive installation (will prompt for admin credentials)
# DDEV uses default credentials: db/db/db for database
ddev exec drush site:install standard --db-url=mysql://db:db@db/db

# Option 2: Automated installation using environment variables
# Set ADMIN_USER and ADMIN_PASS environment variables before running:
ddev exec drush site:install standard \
  --db-url=mysql://db:db@db/db \
  --account-name="${ADMIN_USER}" \
  --account-pass="${ADMIN_PASS}" \
  --yes

# Enable development modules
ddev exec drush pm:enable devel kint webprofiler -y

# Clear caches
ddev exec drush cr

# Launch site in browser
ddev launch
```

### Essential DDEV Commands
```bash
# Environment management
ddev start                # Start development environment
ddev stop                 # Stop environment
ddev restart              # Restart environment
ddev delete               # Delete environment (careful!)

# Database operations
ddev snapshot             # Create database snapshot
ddev restore-snapshot     # Restore database snapshot
ddev import-db            # Import database from file
ddev export-db            # Export database to file

# Development tools
ddev exec <command>       # Execute command in container
ddev ssh                  # SSH into web container
ddev logs                 # View container logs
ddev describe             # Show environment details
ddev launch               # Open site in browser
```

### DDEV Configuration
Create `.ddev/config.yaml` for project-specific settings:

```yaml
# .ddev/config.yaml
type: drupal
docroot: web
php_version: "8.1"
webserver_type: nginx-fpm
router_http_port: "80"
router_https_port: "443"
xdebug_enabled: false
additional_hostnames: []
additional_fqdns: []

# Drupal-specific settings
disable_settings_management: false
web_environment:
  - DRUSH_OPTIONS_URI=https://my-drupal-project.ddev.site
```
