# AGENTS.md Document Structure

This guide defines the ordering and composition of sections in the final AGENTS.md file.

## Section Order

1. **Header** - AI Agent Instructions notice (from `references/sections/project-overview.md` header)
2. **Project Overview** - Include `references/sections/project-overview.md` (with environment-specific placeholders filled)
3. **Environment Setup** - Include `references/environments/{detected}/setup.md`
4. **Code Style and Standards** - Include `references/sections/code-style.md`
5. **Drupal Development Patterns** - Include all pattern sections:
   - `references/sections/services-di.md`
   - `references/sections/entity-api.md`
   - `references/sections/plugin-system.md`
   - `references/sections/hooks.md`
   - `references/sections/forms-api.md`
   - `references/sections/routes-controllers.md`
6. **Security & Performance Guidelines** - Include:
   - `references/sections/security.md`
   - `references/sections/caching.md`
7. **Development Workflow** - Include `references/environments/{detected}/commands.md`
8. **Debugging** - Include `references/environments/{detected}/debugging.md`
9. **Testing & Quality Assurance** - Include `references/sections/testing.md`
10. **Advanced Patterns** - Include `references/sections/batch-queue-ajax.md`
11. **Troubleshooting** - Include `references/environments/{detected}/troubleshooting.md` (if exists)
12. **Additional Resources** - Include `references/sections/resources.md`

## Placeholder Replacement

In `references/sections/project-overview.md`, replace:
- `{DRUPAL_VERSION}` with detected Drupal version (e.g., "10.x+" or "11.x+")
- `{ENVIRONMENT_NAME}` with detected environment (DDEV, Lando, Docker Compose, or Vanilla)
- `{PHP_VERSION}` with detected PHP version requirement
- `{WEBSERVER}` with appropriate webserver (Nginx, Apache, or both)
- `{ENVIRONMENT_TOOLS}` with environment-specific tools (e.g., ", DDEV CLI" for DDEV)
- `{ENVIRONMENT_NOTE}` with environment-specific note from `references/environments/{detected}/overview.md`

**For Docker Compose environments**, in `references/environments/docker-compose/*.md` files, replace:
- `{COMPOSE_COMMAND}` with detected compose command from `detected.docker_compose.compose_command` (either `docker compose` for V2 or `docker-compose` for V1)
- `{COMPOSE_FILE}` with detected compose filename from `detected.docker_compose.compose_file` (e.g., `compose.yaml`, `docker-compose.yml`)
- `{WEB_SERVICE}` with detected web service name from `detected.docker_compose.web_service` (e.g., `web`, `php`, `app`)
- `{DB_SERVICE}` with detected database service name from `detected.docker_compose.db_service` (e.g., `db`, `mariadb`, `mysql`)
- `{DB_NAME}` with detected database name from `detected.docker_compose.db_name`
- `{DB_USER}` with detected database user from `detected.docker_compose.db_user`
- `{DB_PASSWORD}` with detected database password from `detected.docker_compose.db_password`
- `{DB_HOST}` with detected database host from `detected.docker_compose.db_host` (usually same as `{DB_SERVICE}`)

These placeholders ensure that all Docker Compose commands use the correct service names and database credentials from the user's actual configuration, and use the appropriate compose command variant (V1 or V2) based on what's available on their system.

## Version-Specific Adjustments

For Drupal 11.x:
- Note PHP 8.2+ requirement
- Mention deprecated hooks and APIs
- Update patterns to reflect Drupal 11 changes

For Drupal 10.x:
- Standard patterns apply
- PHP 8.1+ requirement

## Environment-Specific Content

Each environment directory (`ddev/`, `lando/`, `docker-compose/`, `vanilla/`) contains:
- `overview.md` - Environment-specific overview (used in Project Overview section)
- `setup.md` - Setup instructions
- `commands.md` - Development workflow commands
- `debugging.md` - Debugging tools and techniques
- `troubleshooting.md` - Troubleshooting guide (optional, may not exist for all environments)

## Composition Instructions

When composing the final AGENTS.md:

1. Start with the header from `project-overview.md`
2. Fill in Project Overview with detected values
3. Follow the section order above
4. Include all shared sections from `references/sections/`
5. Include environment-specific sections from `references/environments/{detected}/`
6. Ensure smooth transitions between sections
7. Maintain consistent formatting and markdown structure
