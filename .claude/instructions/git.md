# Comprehensive Git Instructions for Drupal Development

## 1. Git Workflow

### Workflow Type
- **Feature Branch Workflow** with Pull Requests
- **Main Branches**:
  - `main` / `master` — Production-ready code (protected)
  - `develop` — Integration branch for features
- **Supporting Branches**:
  - `feature/*` — Feature development
  - `bugfix/*` — Bug fixes
  - `hotfix/*` — Production hotfixes
  - `docs/*` — Documentation updates
  - `refactor/*` — Code refactoring

---

## 2. Branch Naming Convention

### Format

```
<type>/<ticket-number>-<description>
```

### Examples

```bash
feature/329-search-feature-styling
bugfix/456-fix-header-mobile-display
hotfix/critical-security-patch
docs/API-documentation-update
refactor/optimize-database-queries
```

### Rules

- Use **lowercase** (except acronyms like API, CSS)
- Use **hyphens** to separate words (no spaces, no underscores)
- Include **ticket number** from issue tracker
- Be **descriptive** — branch name should indicate purpose
- Keep under **50 characters** when possible
- Use **imperative mood**: "add", "fix", "update"

---

## 3. Commit Message Guidelines

### Format

```
#<TICKET_NUMBER>: <Description>.
```

### Examples

```
#329: Update search feature styling.
#445: Add user profile page.
#456: Fix header mobile display.
```

### Rules

1. Start with ticket number (e.g., `#329`)
2. Colon + space after number
3. Capitalize first letter of description
4. End with period
5. Max 72 characters
6. Present tense imperative: "Add", "Fix", "Update", "Remove"
7. One logical change per commit

### Good vs Bad

```bash
# ✅ GOOD
#329: Update search feature styling.
#445: Add user profile avatar upload.
#456: Fix mobile menu alignment issue.

# ❌ BAD
updates the search styling
Fixed header on mobile
#329 search styling updates
329: search styling
```

### Extended Format

For complex changes:

```
#329: Add search feature styling.

- Update search input styling for better UX
- Improve search results display
- Add loading indicator
- Fix accessibility issues with form labels

Fixes #329
Closes #430
```

---

## 4. Daily Workflow

### Starting Work

```bash
git checkout develop
git pull origin develop
git checkout -b feature/329-search-feature-styling
git push -u origin feature/329-search-feature-styling
```

### During Development

```bash
git status
git add .
git commit -m "#329: Update search feature styling."
git push origin feature/329-search-feature-styling
```

### Keeping Updated

```bash
git fetch origin
git rebase origin/develop
# Resolve conflicts if any, then:
git push origin feature/329-search-feature-styling --force-with-lease
```

---

## 5. Pull Requests

### Before Creating PR

```bash
git fetch origin
git rebase origin/develop
git push origin feature/329-search-feature-styling

# Run quality checks
vendor/bin/phpunit
vendor/bin/phpcs
vendor/bin/phpstan
```

### PR Description Template

```markdown
## Description
Brief description of what this PR does.

## Related Ticket
Fixes #329

## Type of Change
- [ ] Feature
- [ ] Bugfix
- [ ] Documentation
- [ ] Refactoring

## Changes Made
- Change 1
- Change 2

## Testing
- [ ] Tested locally
- [ ] Unit tests pass
- [ ] Manual testing completed

## Checklist
- [ ] Code follows project style guidelines
- [ ] PHPDoc comments added
- [ ] Tests written/updated
- [ ] Drupal best practices followed
```

---

## 6. Common Workflows

### Feature Development

```bash
git checkout develop && git pull origin develop
git checkout -b feature/329-search-styling
# Make changes
git add . && git commit -m "#329: Update search styling."
git push origin feature/329-search-styling
# Create PR, get approval, merge
git checkout develop && git pull origin develop
git branch -d feature/329-search-styling
```

### Hotfix

```bash
git checkout main && git pull origin main
git checkout -b hotfix/critical-security-patch
# Fix the issue
git add . && git commit -m "#999: Apply security patch."
git push origin hotfix/critical-security-patch
# Create PR to main, also merge to develop
```

---

## 7. Drupal-Specific Patterns

### Committing Configuration

```bash
drush config:export
git add config/
git commit -m "#329: Export updated form configuration."
```

### Committing Module Changes

```bash
git add modules/custom/my_module/my_module.services.yml
git add modules/custom/my_module/src/Controller/MyController.php
git commit -m "#329: Add user management service and controller."
```

---

## 8. Best Practices

### Commit Hygiene
- ✅ Commit often (small, logical changes)
- ✅ Write descriptive commit messages
- ✅ Don't commit commented-out code
- ✅ Don't commit debug code or breakpoints
- ✅ Don't commit environment variables or secrets

### Branch Hygiene
- ✅ Create branch from latest develop
- ✅ Keep branch up to date with develop
- ✅ Delete merged branches
- ✅ Never force-push to shared branches

### Push/Pull Practice
- ✅ Pull before pushing
- ✅ Use `--force-with-lease` instead of `--force`
- ✅ Never force-push to main or develop

---

## 9. Reverting & Undoing

```bash
# Undo uncommitted changes
git checkout .

# Undo last commit (keep changes)
git reset --soft HEAD~1

# Undo last commit (discard changes)
git reset --hard HEAD~1

# Revert a pushed commit
git revert <commit-hash>

# Recover deleted branch
git reflog
git checkout -b recover-branch <commit-hash>
```

---

## 10. Stashing

```bash
git stash save "WIP: search feature styling"
git stash list
git stash pop
git stash apply stash@{0}
```

---

## 11. Quick Reference Checklist

### Before Pushing
- [ ] Code follows project standards
- [ ] All tests pass locally
- [ ] No debug code or console.log
- [ ] Commit messages follow format

### Before Creating PR
- [ ] Branch rebased on latest develop
- [ ] Meaningful PR description
- [ ] Related issues linked
- [ ] All tests passing

### After Merging
- [ ] Branch deleted locally and remotely
- [ ] Develop pulled locally

