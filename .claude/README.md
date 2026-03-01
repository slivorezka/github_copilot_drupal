# Claude Code Instructions for the Drupal Project

## Overview

This project uses Claude Code configuration files in the `.claude/` directory. They provide Claude Code with project-specific context, coding standards, and reusable slash commands for Drupal 10 development.

The `claude-skill/` folder contains source copies for reference. The active files live in `.claude/`.

## See official Claude Code [documentation](https://docs.anthropic.com/en/docs/claude-code/memory) about memory and custom instructions.

## Active Structure (`.claude/`)

```
.claude/
├── CLAUDE.md                              # Main project instructions (auto-loaded)
├── instructions/                          # Topic-specific context files
│   ├── drupal.md                          # Drupal 10 module development rules
│   ├── php.md                             # PHP 8.3 coding standards
│   ├── js.md                              # JavaScript / Drupal Behaviors rules
│   ├── twig.md                            # Twig templating best practices
│   └── git.md                             # Git workflow and commit conventions
└── commands/                              # Slash commands
    ├── create-drupal-service.md           # Generate a Drupal service class
    ├── create-drupal-plugin.md            # Generate a Drupal plugin class
    ├── drupal-performance-review.md       # Analyze code for performance issues
    ├── drupal-refactor-to-di.md           # Refactor static calls to dependency injection
    └── git-commit.md                      # Format commit messages per branch rules
```

## How It Works

### `CLAUDE.md`

Claude Code reads `.claude/CLAUDE.md` at the start of every session. It contains:
- Project overview (stack, versions)
- Coding standards and architecture rules
- References to detailed topic files via `@.claude/instructions/` paths
- Git commit conventions

### Instructions

Detailed instruction files in `.claude/instructions/` provide deep context for specific technologies. They are referenced from `CLAUDE.md` and loaded as needed.

### Slash Commands

Slash commands are Markdown files in `.claude/commands/`. Each file is a prompt template that accepts `$ARGUMENTS` — a free-text input the user provides when invoking the command.

| Slash Command                        | Purpose                                    |
|--------------------------------------|--------------------------------------------|
| `/user:create-drupal-service`        | Scaffold a new Drupal service class        |
| `/user:create-drupal-plugin`         | Scaffold a new Drupal plugin class         |
| `/user:drupal-performance-review`    | Run a performance review on selected code  |
| `/user:drupal-refactor-to-di`        | Refactor code to use dependency injection  |
| `/user:git-commit`                   | Generate a commit message for the branch   |

**Example usage:**
```
/user:create-drupal-service UserProfileService in user_profiles module — manages user profile CRUD operations with database, entity_type.manager, current_user, logger dependencies
```

## Mapping: GitHub Copilot → Claude Code

| GitHub Copilot File                                  | Claude Code Equivalent                       |
|------------------------------------------------------|----------------------------------------------|
| `.github/copilot-instructions.md`                    | `.claude/CLAUDE.md`                          |
| `.github/git-commit-instructions.md`                 | `.claude/CLAUDE.md` + `.claude/commands/git-commit.md` |
| `.github/instructions/drupal.instructions.md`        | `.claude/instructions/drupal.md`             |
| `.github/instructions/php.instructions.md`           | `.claude/instructions/php.md`                |
| `.github/instructions/js.instructions.md`            | `.claude/instructions/js.md`                 |
| `.github/instructions/twig.instructions.md`          | `.claude/instructions/twig.md`               |
| `.github/instructions/git.instructions.md`           | `.claude/instructions/git.md`                |
| `.github/prompts/create-drupal-service.prompt.md`    | `.claude/commands/create-drupal-service.md`  |
| `.github/prompts/create-drupal-plugin.prompt.md`     | `.claude/commands/create-drupal-plugin.md`   |
| `.github/prompts/drupal-performance-review.prompt.md`| `.claude/commands/drupal-performance-review.md` |
| `.github/prompts/drupal-refactor-to-di.prompt.md`    | `.claude/commands/drupal-refactor-to-di.md`  |
