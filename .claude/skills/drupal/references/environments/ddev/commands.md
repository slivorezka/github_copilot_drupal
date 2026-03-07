## DDEV Development Workflow

### Project Structure
- **Modules** → `web/modules/custom/<module_name>`
- **Themes** → `web/themes/custom/<theme_name>`
- **Configuration** → Export with `ddev exec drush config:export`
- **Profiles** → `web/profiles/custom/<profile_name>`

### Essential Development Commands
```bash
# Cache management (run inside DDEV)
ddev exec drush cr                    # Clear all caches
ddev exec drush cache:rebuild         # Alternative cache clear

# Configuration management
ddev exec drush config:export         # Export configuration
ddev exec drush config:import         # Import configuration

# Database operations
ddev snapshot                         # Create snapshot before changes
ddev exec drush updatedb              # Run database updates
```

### Version Control Workflow
- **Commit messages**: Format `[#123456] Brief descriptive title`
- **Branch from**: `develop` branch for features
- **Atomic commits**: One logical change per commit
- **Before pushing**: Run linting and tests
