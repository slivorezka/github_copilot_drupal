---
name: drupal-agents-generator
description: Generates a composable AGENTS.md file for Drupal projects by detecting the development environment (DDEV, Lando, Docker Compose, or vanilla) and Drupal version, then assembling modular content sections accordingly.
---

# Drupal AGENTS.md Generator

This skill generates a customized `AGENTS.md` file for Drupal projects by detecting the development environment and composing appropriate sections from modular content.

## How to Use This Skill

1. **Detect Environment**: **IMPORTANT**: Always use the detection script (`scripts/detect-environment.php`) via `run_terminal_cmd` for reliable detection. The script handles hidden directories correctly.

   **If you must check manually** (not recommended), use `read_file` or `list_dir` to check for hidden files/directories. **DO NOT use `glob_file_search`** as it cannot find hidden directories/files (those starting with `.`):
   - Use `read_file('.ddev/config.yaml')` or `list_dir('.ddev')` to check for DDEV → DDEV
   - Use `read_file('.lando.yml')` to check for Lando → Lando
   - Use `read_file()` or `glob_file_search` for `compose.yaml`, `compose.yml`, `docker-compose.yaml`, or `docker-compose.yml` → Docker Compose (checked in priority order)
   - If none found → Vanilla/Traditional setup

2. **Detect Drupal Version**: Read `composer.json` to determine:
   - Drupal core version from `drupal/core` or `drupal/core-recommended`
   - PHP version requirements
   - Adjust content accordingly (Drupal 10 vs 11 differences)

3. **Compose AGENTS.md**: Follow the structure guide in `assets/structure.md` and assemble the document by:
   - Including shared sections from `references/sections/`
   - Inserting environment-specific content from `references/environments/{detected-environment}/`
   - Adjusting version-specific details (e.g., Drupal 11 deprecations, PHP 8.2+ requirements)

4. **Write Output**: Generate the final `AGENTS.md` file in the project root.

## Detection Script

**ALWAYS use the detection script** (`scripts/detect-environment.php`) via `run_terminal_cmd` to detect the environment. This is the most reliable method because:

1. The script uses PHP's `file_exists()` and `is_dir()` which correctly handle hidden directories (like `.ddev/`)
2. It provides consistent, structured JSON output
3. It handles edge cases and path resolution correctly

**To run the detection script:**
```bash
php scripts/detect-environment.php
```

Or if running from the skill directory:
```bash
php surge/skills/drupal-agents-generator/scripts/detect-environment.php
```

The script automatically detects:
- Development environment (DDEV, Lando, Docker Compose, or Vanilla)
- Drupal version from composer.json
- PHP version requirements

The script outputs JSON with detected values for programmatic use. Parse this JSON to determine which environment sections to include.

### Docker Compose Detection

When Docker Compose is detected, the script performs enhanced detection:

1. **Compose File Detection**: Checks for compose files in priority order (per compose-spec):
   - `compose.yaml` (preferred)
   - `compose.yml`
   - `docker-compose.yaml`
   - `docker-compose.yml`

2. **Command Detection**: Determines which compose command is available:
   - **V2 (preferred)**: `docker compose` - Docker CLI plugin, modern standard
   - **V1 (legacy)**: `docker-compose` - Standalone Python application, deprecated but still in use
   - Falls back to V2 if neither is detected

3. **Service Detection**: Parses the compose file to detect:
   - Web/PHP service name (checks for: `web`, `php`, `app`, `drupal`)
   - Database service name (checks for: `db`, `database`, `mysql`, `mariadb`, `postgres`)

4. **Database Credentials**: Extracts from environment variables:
   - Database name (`MYSQL_DATABASE` or `POSTGRES_DB`)
   - Database user (`MYSQL_USER` or `POSTGRES_USER`)
   - Database password (`MYSQL_PASSWORD` or `POSTGRES_PASSWORD`)
   - Database host (defaults to database service name)

The script outputs a `docker_compose` object with all detected values:

```json
{
  "environment": "docker-compose",
  "docker_compose": {
    "compose_command": "docker compose",
    "compose_file": "compose.yaml",
    "web_service": "php",
    "db_service": "mariadb",
    "db_name": "drupal",
    "db_user": "drupal",
    "db_password": "drupal",
    "db_host": "mariadb"
  }
}
```

### Docker Compose Placeholder Replacement

When composing Docker Compose sections (`references/environments/docker-compose/*.md`), replace all placeholders with detected values:

| Placeholder | Source | Description |
|-------------|--------|-------------|
| `{COMPOSE_COMMAND}` | `detected.docker_compose.compose_command` | Either `docker compose` (V2) or `docker-compose` (V1) |
| `{COMPOSE_FILE}` | `detected.docker_compose.compose_file` | Detected compose filename |
| `{WEB_SERVICE}` | `detected.docker_compose.web_service` | Web/PHP service name |
| `{DB_SERVICE}` | `detected.docker_compose.db_service` | Database service name |
| `{DB_NAME}` | `detected.docker_compose.db_name` | Database name |
| `{DB_USER}` | `detected.docker_compose.db_user` | Database username |
| `{DB_PASSWORD}` | `detected.docker_compose.db_password` | Database password |
| `{DB_HOST}` | `detected.docker_compose.db_host` | Database hostname |

**Example**: A command template `{COMPOSE_COMMAND} exec {WEB_SERVICE} drush cr` becomes `docker compose exec php drush cr` when the detected web service is `php` and Docker Compose V2 is available.

## Content Structure

- **Shared Sections** (`references/sections/`): Content that applies to all environments
  - Code style and standards
  - Drupal development patterns (Services, Entity API, Plugins, Hooks, Forms, Routes)
  - Security and performance guidelines
  - Testing and quality assurance
  - Advanced patterns (Batch, Queue, AJAX)

- **Environment Sections** (`references/environments/`): Environment-specific content
  - Setup instructions
  - Command syntax (e.g., `ddev exec drush` vs `drush`)
  - Debugging tools and techniques
  - Troubleshooting guides

## Version Handling

- **Drupal 10.x**: Standard patterns and APIs
- **Drupal 11.x**: Note deprecated hooks, new APIs, PHP 8.2+ requirements, updated patterns

Check the detected Drupal version and adjust content accordingly in the final output.

## Output Format

The generated `AGENTS.md` follows the structure defined in `assets/structure.md`:
1. Header with AI Agent Instructions notice
2. Project Overview (with detected environment/version)
3. Environment Setup (environment-specific)
4. Code Style and Standards
5. Drupal Development Patterns
6. Security & Performance Guidelines
7. Development Workflow (environment-specific commands)
8. Debugging (environment-specific)
9. Testing & Quality Assurance
10. Advanced Patterns
11. Troubleshooting (environment-specific)
12. Additional Resources

## Example Usage

When invoked, this skill should:
1. **Run the detection script**: Execute `php scripts/detect-environment.php` (or `php surge/skills/drupal-agents-generator/scripts/detect-environment.php` if from skill directory) using `run_terminal_cmd`
2. **Parse the JSON output** to determine:
   - Environment type (DDEV, Lando, Docker Compose, or Vanilla)
   - Drupal version (e.g., Drupal 10.x or 11.x)
   - PHP version requirements
3. **Compose AGENTS.md** with:
   - Environment-specific setup and commands (from `references/environments/{detected-environment}/`)
   - Shared Drupal patterns and standards (from `references/sections/`)
   - Version-appropriate content (Drupal 10 vs 11 differences)
   - Environment-specific debugging and troubleshooting sections

**Critical**: Do not attempt to detect the environment manually using `glob_file_search` for hidden directories like `.ddev/` - this will fail. Always use the detection script.
