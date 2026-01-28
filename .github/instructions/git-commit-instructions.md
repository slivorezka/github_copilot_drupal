## Git & Commit Message Rules

When suggesting commit messages, the format must depend on the current Git branch name.

### Branch-based Rules

-   **For `feature/*` branches**:
  -   The commit message must start with the ticket ID from the branch name.
  -   **Format**: `#<number>: <description>.`
  -   **Example**: For a branch named `feature/123-login`, a valid commit message would be `#123: Add login form validation.`

### General Rules

-   End the commit message with a period.
-   Keep the message concise, with a maximum of 72 characters.
-   Ensure each commit represents a single logical change.
