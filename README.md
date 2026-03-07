# AI-Powered Drupal Development Instructions

A collection of AI coding assistant configurations for Drupal 10 projects. This repository provides structured instructions, coding standards, and reusable commands for **GitHub Copilot** and **Claude Code** to ensure consistent, high-quality Drupal development.

## What's Inside

### Shared Standards

Both tools enforce the same Drupal development rules:

- **Drupal 10.x** on a LAMP stack with **PHP 8.3**
- Strict [Drupal Coding Standards](https://www.drupal.org/docs/develop/standards)
- `declare(strict_types=1);` in all PHP files
- Dependency injection everywhere (no `\Drupal::` in classes)
- Comprehensive PHPDoc blocks, typed properties, and return types
- Business logic in services, not controllers
- Security best practices (input sanitization, output escaping, permission checks)
- PHPUnit testing for custom services and business logic
- Git commit conventions: `#<TICKET>: <Description>.`

### Topic-Specific Instructions

| Topic   | Description                                  |
|---------|----------------------------------------------|
| Drupal  | Module development, hooks, plugins, entities |
| PHP     | PHP 8.3 coding standards and best practices  |
| Twig    | Templating rules and preprocess patterns     |
| JS      | Drupal Behaviors, AJAX API, `once()` usage   |
| Git     | Branch naming, commit message format         |

### Reusable Commands / Prompts

| Command                      | Description                                   |
|------------------------------|-----------------------------------------------|
| Create Drupal Service        | Scaffold a new Drupal service class           |
| Create Drupal Plugin         | Scaffold a new Drupal plugin class            |
| Drupal Performance Review    | Analyze code for performance issues           |
| Drupal Refactor to DI        | Refactor static calls to dependency injection |
| Git Commit                   | Generate a commit message per branch rules    |

## GitHub Copilot

Configuration lives in `.github/`:

```
.github/
├── copilot-instructions.md                        # Main instructions (auto-loaded)
├── git-commit-instructions.md                     # Git commit conventions
├── instructions/                                  # Topic-specific instruction files
│   ├── drupal.instructions.md
│   ├── php.instructions.md
│   ├── js.instructions.md
│   ├── twig.instructions.md
│   └── git.instructions.md
└── prompts/                                       # Reusable prompt templates
    ├── create-drupal-service.prompt.md
    ├── create-drupal-plugin.prompt.md
    ├── drupal-performance-review.prompt.md
    └── drupal-refactor-to-di.prompt.md
```

See the official [GitHub Copilot documentation](https://docs.github.com/en/copilot/how-tos/configure-custom-instructions/add-repository-instructions) about custom instructions.

## Claude Code

Configuration lives in `.claude/`:

```
.claude/
├── CLAUDE.md                                      # Main project instructions (auto-loaded)
├── settings.json                                  # Environment and permission settings
├── instructions/                                  # Topic-specific context files
│   ├── drupal.md
│   ├── php.md
│   ├── js.md
│   ├── twig.md
│   └── git.md
└── commands/                                      # Slash commands
    ├── create-drupal-service.md
    ├── create-drupal-plugin.md
    ├── drupal-performance-review.md
    ├── drupal-refactor-to-di.md
    └── git-commit.md
```

### Slash Command Usage

```
/user:create-drupal-service UserProfileService in user_profiles module — manages user profile CRUD operations
/user:create-drupal-plugin MyCustomBlock as Block plugin in my_module
/user:drupal-performance-review src/Service/DataProcessor.php
/user:drupal-refactor-to-di src/Controller/MyController.php
/user:git-commit
```

See the official [Claude Code documentation](https://docs.anthropic.com/en/docs/claude-code/memory) about memory and custom instructions.

## File Mapping

| GitHub Copilot                                       | Claude Code                                      |
|------------------------------------------------------|--------------------------------------------------|
| `.github/copilot-instructions.md`                    | `.claude/CLAUDE.md`                              |
| `.github/git-commit-instructions.md`                 | `.claude/CLAUDE.md` + `.claude/commands/git-commit.md` |
| `.github/instructions/drupal.instructions.md`        | `.claude/instructions/drupal.md`                 |
| `.github/instructions/php.instructions.md`           | `.claude/instructions/php.md`                    |
| `.github/instructions/js.instructions.md`            | `.claude/instructions/js.md`                     |
| `.github/instructions/twig.instructions.md`          | `.claude/instructions/twig.md`                   |
| `.github/instructions/git.instructions.md`           | `.claude/instructions/git.md`                    |
| `.github/prompts/create-drupal-service.prompt.md`    | `.claude/commands/create-drupal-service.md`      |
| `.github/prompts/create-drupal-plugin.prompt.md`     | `.claude/commands/create-drupal-plugin.md`       |
| `.github/prompts/drupal-performance-review.prompt.md`| `.claude/commands/drupal-performance-review.md`  |
| `.github/prompts/drupal-refactor-to-di.prompt.md`    | `.claude/commands/drupal-refactor-to-di.md`      |

## How to Use

1. **Copy** the `.github/` and/or `.claude/` directories into your Drupal project root
2. **Customize** the instructions to match your project's specific needs (module names, services, etc.)
3. Start coding with your AI assistant — it will automatically follow the configured standards

## License

MIT
