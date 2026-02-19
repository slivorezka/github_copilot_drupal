## Git & Commit Message Rules

Commit message format MUST depend on the current Git branch.

### Branch-based rules

- `feature/*`
  - Commit message MUST start with a ticket ID from the branch name
  - Format: `<number>: <description>.`
  - Example:
    - Branch: `feature/123-login`
    - Commit: `123: Add login form validation.`

### General rules

- Add period at the end
- Max 72 characters
- One logical change per commit
