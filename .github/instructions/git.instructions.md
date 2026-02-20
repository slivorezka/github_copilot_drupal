# Git Instructions

## Purpose
Use these Git rules for all Drupal 10 and above work in this repository to keep history clean, reviews easy, and releases predictable.

## Branching
- Create a new branch for every change.
- Use short, descriptive names; keep one feature or fix per branch.
- Prefer prefixes like `feature/`, `fix/`, `chore/`, or `docs/`.
- Keep long-lived branches limited to `main` (or the repo default) and release branches.

## Commit Message Format
- Start with the ticket ID from the branch name.
- Format: `<number>: <description>.`
- Use imperative mood for the description.
- End with a period; max 72 characters.
- One logical change per commit.
- Example:
  - Branch: `feature/123-login`
  - Commit: `123: Add login form validation.`

## Commit Content
- Keep diffs focused and minimal; avoid mixed refactors and feature work.
- Do not commit generated files unless required by the workflow.
- Remove dead code, unused imports, and debug statements before committing.
- Avoid committing `vendor/`, `node_modules/`, or build artifacts.

## Reviews and Merges
- Open a pull request for review; link related tickets.
- Ensure tests and coding standards pass before requesting review.
- Prefer squash merging for small changes; keep meaningful commits for larger work.
- Ensure release notes or upgrade notes are added when behavior changes.

## Rewriting History
- Do not rewrite history on shared branches.
- Use `git rebase` only on your local branch and before opening a PR.
- Resolve conflicts carefully and run tests after rebasing.

## Release Hygiene
- Tag releases when required by the project process.
- Keep the default branch stable and deployable.
- Follow Drupal core update guidance when touching core or dependency versions.
