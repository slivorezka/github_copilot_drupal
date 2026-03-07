---
name: ddev-environment
description: Reference documentation for DDEV-based Drupal development environments. Covers setup, commands, debugging, and troubleshooting specific to DDEV.
---

# DDEV Environment Reference

This directory contains modular reference sections for Drupal projects using DDEV as the local development environment.

## Files

| File                | Description                                                                 |
|---------------------|-----------------------------------------------------------------------------|
| `overview.md`       | Project overview snippet with DDEV-specific environment details             |
| `setup.md`          | DDEV installation, project initialization, and configuration                |
| `commands.md`       | Development workflow commands prefixed with `ddev exec`                     |
| `debugging.md`      | Debugging techniques and tools available in DDEV (Xdebug, logs, Drush)     |
| `troubleshooting.md`| Common DDEV issues and their solutions                                      |

## Usage

These files are consumed by the parent skill (`drupal/SKILL.md`) when the environment detection script identifies DDEV as the active development environment. Each file provides a section that gets composed into the final `AGENTS.md` output.

## Key DDEV Conventions

- All Drupal CLI commands are run via `ddev exec` (e.g., `ddev exec drush cr`)
- Default database credentials: `db` / `db` / `db` (user / password / database)
- Configuration lives in `.ddev/config.yaml`
- Xdebug is toggled via `xdebug_enabled` in `.ddev/config.yaml`
- Database snapshots use `ddev snapshot` / `ddev restore-snapshot`

## Detection

DDEV is detected by checking for the `.ddev/` directory (specifically `.ddev/config.yaml`) in the project root. The detection script (`scripts/detect-environment.php`) handles this automatically.
