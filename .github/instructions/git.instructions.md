# Comprehensive Git Instructions for Drupal Development

## 1. Git Workflow Overview

### Our Git Workflow
- **Type**: Feature Branch Workflow with Pull Requests
- **Main Branches**:
  - `main` or  `master` - Production-ready code (protected branch)
  - `develop` - Integration branch for features
- **Supporting Branches**:
  - `feature/*` - Feature development
  - `bugfix/*` - Bug fixes
  - `hotfix/*` - Production hotfixes
  - `docs/*` - Documentation updates
  - `refactor/*` - Code refactoring

---

## 2. Initial Setup

### Configure Git Locally

```bash
# Set your name (global)
git config --global user.name "Your Name"

# Set your email (global)
git config --global user.email "your.email@example.com"

# For project-specific overrides
git config user.name "Your Name"
git config user.email "your.email@example.com"

# View current config
git config --list
```

### SSH Key Setup

```bash
# Generate SSH key (if you don't have one)
ssh-keygen -t ed25519 -C "your.email@example.com"

# Add to SSH agent
ssh-add ~/.ssh/id_ed25519

# Copy public key to GitHub/GitLab
cat ~/.ssh/id_ed25519.pub
```

### Clone the Repository

```bash
# Clone via SSH (recommended)
git clone git@github.com:organization/repo.git

# Clone via HTTPS (if SSH not configured)
git clone https://github.com/organization/repo.git

# Navigate to project
cd repo
```

---

## 3. Branch Naming Convention

### Branch Name Format

```
<type>/<ticket-number>-<description>
```

### Examples

```bash
# Feature branches
feature/329-search-feature-styling
feature/445-user-profile-page
feature/102-email-notifications

# Bugfix branches
bugfix/456-fix-header-mobile-display
bugfix/789-resolve-form-validation-issue

# Hotfix branches (from main)
hotfix/critical-security-patch
hotfix/database-migration-rollback

# Documentation branches
docs/API-documentation-update
docs/setup-instructions

# Refactoring branches
refactor/optimize-database-queries
refactor/simplify-form-validation
```

### Naming Rules

- **Use lowercase** (except acronyms like API, CSS)
- **Use hyphens** to separate words (never spaces or underscores)
- **Include ticket number** from issue tracker (e.g., #329)
- **Be descriptive** - branch name should indicate purpose
- **Keep under 50 characters** when possible
- **Use imperative mood** - "add", "fix", "update", not "adding", "fixed"

---

## 4. Commit Message Guidelines

### Commit Message Format

```
#<TICKET_NUMBER>: <Description>.
```

### Examples

```
#329: Update search feature styling.
#445: Add user profile page.
#456: Fix header mobile display.
#789: Resolve form validation issue.
```

### Commit Message Rules

1. **Start with ticket number** (e.g., `#329`)
2. **Use colon after number** followed by space
3. **Capitalize first letter** of description
4. **End with period** (full stop)
5. **Keep message under 72 characters**
6. **Use present tense** imperative: "Add", "Fix", "Update", "Remove"
7. **One logical change per commit**

### Good vs Bad Examples

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
updated search feature
```

### Extended Commit Message Format

For complex changes, use extended format:

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

## 5. Daily Workflow

### Before Starting Work

```bash
# Update develop branch
git checkout develop
git pull origin develop

# Create feature branch
git checkout -b feature/329-search-feature-styling

# Set upstream for easy tracking
git push -u origin feature/329-search-feature-styling
```

### During Development

#### Check Status

```bash
# See what changed
git status

# See detailed changes
git diff

# See staged changes
git diff --staged
```

#### Stage Changes

```bash
# Stage specific file
git add path/to/file.php

# Stage all changes
git add .

# Stage with interactive mode (choose what to add)
git add -p
```

#### Make Commits

```bash
# Commit staged changes
git commit -m "#329: Update search feature styling."

# Amend last commit (if not pushed)
git commit --amend

# Amend without changing message
git commit --amend --no-edit
```

#### Check Local Commits

```bash
# View recent commits
git log --oneline -10

# View commits for specific file
git log --oneline path/to/file.php

# View commits with details
git log --pretty=format:"%h %ad %s" --date=short -10
```

#### Push to Remote

```bash
# Push branch
git push origin feature/329-search-feature-styling

# Force push (only if not shared - use with caution!)
git push origin feature/329-search-feature-styling --force-with-lease
```

---

## 6. Keeping Branch Updated

### Sync with develop

```bash
# Fetch latest from remote
git fetch origin

# Rebase on develop (preferred for feature branches)
git rebase origin/develop

# Or merge if rebase causes issues
git merge origin/develop

# Resolve conflicts if any
# Edit conflicted files, then:
git add path/to/conflicted/file.php
git rebase --continue

# Push updated branch
git push origin feature/329-search-feature-styling --force-with-lease
```

### Rebase vs Merge

```bash
# REBASE (preferred for feature branches - cleaner history)
git rebase origin/develop
# Results in linear history

# MERGE (use for integration branches)
git merge origin/develop
# Preserves merge history
```

---

## 7. Creating Pull Requests

### Before Creating PR

```bash
# Ensure branch is up to date
git fetch origin
git rebase origin/develop

# Push final changes
git push origin feature/329-search-feature-styling

# Verify tests pass locally
vendor/bin/phpunit

# Check code quality
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
- [ ] Performance

## Changes Made
- Change 1
- Change 2
- Change 3

## Testing
- [ ] Tested locally
- [ ] Unit tests pass
- [ ] Manual testing completed
- Describe testing approach

## Screenshots (if applicable)
Add screenshots or videos for UI changes

## Checklist
- [ ] Code follows project style guidelines
- [ ] PHPDoc comments added
- [ ] Tests written/updated
- [ ] No breaking changes
- [ ] Drupal best practices followed
```

---

## 8. Code Review Process

### For PR Authors

```bash
# Address review comments
# Make changes based on feedback
git add .
git commit -m "#329: Address review feedback - improve form validation."
git push origin feature/329-search-feature-styling

# After approval, merge PR (usually through GitHub/GitLab UI)
```

### For Reviewers

```bash
# Fetch and checkout PR branch
git fetch origin pull/<PR_NUMBER>/head:pr-<PR_NUMBER>
git checkout pr-<PR_NUMBER>

# Run tests
vendor/bin/phpunit

# Check code quality
vendor/bin/phpcs

# Review locally then comment on PR
```

---

## 9. Merging & Integration

### Merge PR to develop

```bash
# On GitHub/GitLab, click "Merge Pull Request"
# Choose merge strategy (usually "Create a merge commit" or "Squash and merge")

# Or merge locally
git checkout develop
git pull origin develop
git merge --no-ff feature/329-search-feature-styling
git push origin develop
```

### Clean Up

```bash
# Delete local branch
git branch -d feature/329-search-feature-styling

# Delete remote branch
git push origin --delete feature/329-search-feature-styling

# Clean local branches
git branch -vv | grep gone | awk '{print $1}' | xargs git branch -D
```

---

## 10. Handling Merge Conflicts

### Identify Conflicts

```bash
# See conflicted files
git status

# See conflict details
git diff

# See conflicts in specific file
git diff path/to/conflicted/file.php
```

### Resolve Conflicts

```bash
# Open conflicted file and resolve manually
# Markers: <<<<<<, ======, >>>>>>

# Stage resolved file
git add path/to/conflicted/file.php

# Continue rebase/merge
git rebase --continue
# or
git merge --continue

# If need to abort
git rebase --abort
# or
git merge --abort
```

### Conflict Resolution Examples

```php
// <<<<<<< HEAD (current branch)
// $value = function1();
// =======
// $value = function2();
// >>>>>>> feature/branch

// Resolved:
$value = function1(); // or function2(), or refactor both
```

---

## 11. Reverting Changes

### Undo Uncommitted Changes

```bash
# Discard changes in working directory
git checkout path/to/file.php

# Discard all changes
git checkout .

# Unstage staged changes (keep in working directory)
git reset HEAD path/to/file.php

# Discard staged changes
git reset --hard HEAD
```

### Undo Committed Changes (not pushed)

```bash
# View commit to revert
git log --oneline -5

# Undo last commit, keep changes
git reset --soft HEAD~1

# Undo last commit, discard changes
git reset --hard HEAD~1

# Undo specific commit
git revert <commit-hash>
```

### Undo Pushed Changes

```bash
# Create new commit that reverts changes
git revert <commit-hash>
git push origin feature/329-search-feature-styling

# OR rebase (only if branch not shared)
git rebase -i HEAD~3  # Interactive rebase
git push origin feature/329-search-feature-styling --force-with-lease
```

---

## 12. Stashing Changes

### Save Work in Progress

```bash
# Save current changes without committing
git stash

# Save with description
git stash save "WIP: search feature styling"

# View stashed changes
git stash list

# Apply last stash
git stash pop

# Apply specific stash
git stash pop stash@{0}

# Apply without removing from stash
git stash apply stash@{0}

# Delete stash
git stash drop stash@{0}

# Delete all stashes
git stash clear
```

---

## 13. Cherry-Picking & Backporting

### Cherry-pick Commits

```bash
# Apply specific commit to current branch
git cherry-pick <commit-hash>

# Cherry-pick multiple commits
git cherry-pick <hash1> <hash2> <hash3>

# Cherry-pick range
git cherry-pick <hash1>..<hash2>

# Resolve conflicts if needed
git add .
git cherry-pick --continue
```

### Backport to Hotfix

```bash
# Create hotfix branch from main
git checkout main
git pull origin main
git checkout -b hotfix/critical-security-patch

# Cherry-pick fix from develop
git cherry-pick <commit-hash>

# Push and create PR to main
git push origin hotfix/critical-security-patch
```

---

## 14. Advanced Operations

### Interactive Rebase

```bash
# Rebase last 3 commits
git rebase -i HEAD~3

# Choose actions: pick, reword, squash, fixup, drop
# Pick: use commit
# Reword: use commit, edit message
# Squash: meld into previous commit
# Fixup: like squash, discard log message
# Drop: remove commit

# After rebase
git push origin feature/329-search-feature-styling --force-with-lease
```

### Searching Git History

```bash
# Search for commits by message
git log --grep="#329"

# Search for code changes
git log -S "function_name"

# Search for author
git log --author="Your Name"

# Search by date
git log --after="2025-01-01" --before="2025-12-31"

# Show commit that introduced line
git blame path/to/file.php

# Show commits affecting file
git log -p path/to/file.php
```

### Reflog (Recovery)

```bash
# View all ref changes
git reflog

# Recover deleted branch
git reflog
git checkout -b recover-branch <hash>

# Recover lost commits
git reset --hard <commit-hash>
```

---

## 15. GitHub/GitLab Specific Commands

### GitHub CLI (if using GitHub)

```bash
# Create PR
gh pr create --title "Fix search styling" --body "Description"

# View PR
gh pr view <PR_NUMBER>

# Checkout PR
gh pr checkout <PR_NUMBER>

# Merge PR
gh pr merge <PR_NUMBER>
```

### Syncing Fork

```bash
# Add upstream remote
git remote add upstream git@github.com:organization/repo.git

# Fetch upstream
git fetch upstream

# Rebase on upstream/develop
git rebase upstream/develop

# Push to your fork
git push origin feature/329-search-feature-styling
```

---

## 16. Best Practices

### Commit Hygiene

- ✅ Commit often (small, logical changes)
- ✅ Write descriptive commit messages
- ✅ One feature per commit when possible
- ✅ Don't commit commented-out code
- ✅ Don't commit debug code or breakpoints
- ✅ Don't commit environment variables or secrets

### Branch Hygiene

- ✅ Create branch from latest develop
- ✅ Keep branch up to date with develop
- ✅ Delete merged branches
- ✅ Use consistent naming convention
- ✅ Don't push to main directly
- ✅ Never force-push to shared branches

### Push/Pull Practice

- ✅ Pull before pushing
- ✅ Push regularly to avoid loss
- ✅ Use force-with-lease instead of force
- ✅ Never force-push to main or develop
- ✅ Review changes before pushing

### Code Review

- ✅ Create PR with clear description
- ✅ Link related issues
- ✅ Request specific reviewers
- ✅ Address all feedback
- ✅ Don't merge your own PRs (without approval)
- ✅ Test PR locally before approving

---

## 17. Common Workflows

### Feature Development Workflow

```bash
# 1. Create feature branch
git checkout develop
git pull origin develop
git checkout -b feature/329-search-styling

# 2. Make changes and commit
git add .
git commit -m "#329: Update search styling."

# 3. Keep updated (if develop changes)
git fetch origin
git rebase origin/develop

# 4. Push and create PR
git push origin feature/329-search-styling

# 5. After approval and merge
git checkout develop
git pull origin develop
git branch -d feature/329-search-styling
```

### Bugfix Workflow

```bash
# 1. Create bugfix branch from develop
git checkout develop
git pull origin develop
git checkout -b bugfix/456-fix-mobile-header

# 2. Fix the bug
git add .
git commit -m "#456: Fix header layout on mobile devices."

# 3. Push and create PR
git push origin bugfix/456-fix-mobile-header

# 4. After approval and merge
git fetch origin
git branch -d bugfix/456-fix-mobile-header
```

### Hotfix Workflow

```bash
# 1. Create hotfix branch from main
git checkout main
git pull origin main
git checkout -b hotfix/critical-security-patch

# 2. Fix the issue
git add .
git commit -m "#999: Apply security patch."

# 3. Push and create PR to main
git push origin hotfix/critical-security-patch
# Create PR to main

# 4. Also merge to develop
git checkout develop
git pull origin develop
git merge hotfix/critical-security-patch
git push origin develop

# 5. Clean up
git branch -d hotfix/critical-security-patch
```

### Sync Develop with Main

```bash
# After hotfix is merged to main
git fetch origin
git checkout develop
git pull origin develop
git merge origin/main
git push origin develop
```

---

## 18. Drupal-Specific Patterns

### Committing Drupal Configuration

```bash
# Export configuration
drush config:export

# Commit config changes
git add config/
git commit -m "#329: Export updated form configuration."

# Import configuration (deployment)
drush config:import
```

### Committing Database Migrations

```bash
# Create update hook
drush generate module:install

# Commit migration
git add modules/custom/my_module/my_module.install
git commit -m "#445: Add database migration for user fields."
```

### Committing Module Changes

```bash
# Add new service to services.yml
git add modules/custom/my_module/my_module.services.yml

# Add new controller
git add modules/custom/my_module/src/Controller/MyController.php

# Commit both
git commit -m "#329: Add new user management service and controller."
```

---

## 19. .gitignore Configuration

### Typical Drupal .gitignore

```gitignore
# Drupal
/public/core/
/public/modules/contrib/
/public/themes/contrib/
/public/profiles/contrib/
/public/libraries/
/public/sites/*/files/
/public/sites/*/private/
/public/sites/*/settings.local.php

# Composer
/vendor/
composer.lock

# IDE
.vscode/
.idea/
*.swp
*.swo
*~

# OS
.DS_Store
Thumbs.db

# Local development
.env.local
*.log

# Node modules
node_modules/

# Temporary
/tmp/
/temp/
```

---

## 20. Troubleshooting Common Issues

### Accidentally Committed to Wrong Branch

```bash
# Find commit hash
git log --oneline

# Create correct branch
git checkout -b feature/329-fix

# Reset wrong branch
git checkout develop
git reset --hard HEAD~1

# Branch with commit is ready to push
git push origin feature/329-fix
```

### Accidentally Pushed to Wrong Branch

```bash
# Create PR to correct branch
# Or locally:

# Create new branch with commits
git branch feature/329-fix origin/wrong-branch

# Revert commits on wrong branch
git checkout wrong-branch
git revert <commit-hash>
git push origin wrong-branch

# Push to correct branch
git checkout feature/329-fix
git push origin feature/329-fix
```

### Need to Change Commit Message

```bash
# For last commit (not pushed)
git commit --amend -m "#329: Corrected message."

# For last commit (already pushed)
git commit --amend -m "#329: Corrected message."
git push origin feature/329-fix --force-with-lease

# For older commit
git rebase -i HEAD~3
# Change 'pick' to 'reword' for target commit
# Edit message
git push origin feature/329-fix --force-with-lease
```

### Accidentally Deleted Branch

```bash
# Find deleted branch in reflog
git reflog

# Recover branch
git checkout -b feature/329-fix <commit-hash>

# Or check out directly
git checkout <commit-hash>
```

---

## 21. Git Configuration & Tools

### Useful Git Aliases

```bash
# Add to ~/.gitconfig
[alias]
    st = status
    co = checkout
    br = branch
    ci = commit
    lg = log --oneline --graph --all
    up = pull --rebase
    unstage = reset HEAD --
    last = log -1 HEAD
    undo = reset --soft HEAD~1
    amend = commit --amend --no-edit
    clean-branches = branch -vv | grep gone | awk '{print $1}' | xargs git branch -D
```

### Git Hooks Setup

```bash
# Create pre-commit hook
touch .git/hooks/pre-commit
chmod +x .git/hooks/pre-commit

# Add to .git/hooks/pre-commit
#!/bin/bash
# Run PHP Code Sniffer
vendor/bin/phpcs public/modules/custom public/themes/custom
if [ $? -ne 0 ]; then
    echo "Fix code style issues before committing"
    exit 1
fi
```

### Useful Tools

- **GitKraken** - GUI for Git
- **GitHub Desktop** - GitHub's official GUI
- **SourceTree** - Free Git GUI
- **Sublime Merge** - Modern Git GUI
- **Lazygit** - Terminal UI for Git

---

## 22. Quick Reference Checklist

### Before Pushing
- [ ] Code follows project standards
- [ ] All tests pass locally
- [ ] No debug code or console.log
- [ ] No commented-out code
- [ ] Commit messages follow format
- [ ] Branch is up to date with develop

### Before Creating PR
- [ ] Branch rebased on latest develop
- [ ] Meaningful PR description
- [ ] Related issues linked
- [ ] Screenshots included (if UI changes)
- [ ] All tests passing
- [ ] Code review requested

### Before Merging PR
- [ ] All feedback addressed
- [ ] Approved by reviewers
- [ ] Conflicts resolved
- [ ] Tests pass in CI/CD
- [ ] Code quality checks pass

### After Merging
- [ ] Branch deleted locally
- [ ] Remote branch deleted
- [ ] Develop pulled locally
- [ ] Deployment verified (if applicable)

---

## 23. Resources & References

- [Git Official Documentation](https://git-scm.com/doc)
- [GitHub Guides](https://guides.github.com/)
- [Atlassian Git Tutorials](https://www.atlassian.com/git/tutorials)
- [Git Branching Model](https://nvie.com/posts/a-successful-git-branching-model/)
- [Drupal Git Workflow](https://www.drupal.org/docs/develop/git)
- [Conventional Commits](https://www.conventionalcommits.org/)

---

## 24. Getting Help

### Common Issues
- Ask in Slack #development channel
- Review team git workflow documentation
- Check GitHub/GitLab issue discussions

### Git Documentation
```bash
# Get help for any command
git help <command>

# Example
git help rebase
git help commit
git help merge
```

### Emergency Contacts
- **Git Issues**: Consult team lead or tech lead
- **Repository Access**: Contact DevOps team
- **Merge Conflicts**: Pair with code reviewer

---

## 25. Team Agreements

### Before Committing
- Ensure code is ready for review
- No WIP (Work in Progress) commits pushed
- Follow commit message format strictly
- Only your own code in commits

### Before Merging
- Minimum 1 approved review required
- All CI/CD checks must pass
- Conflicts must be resolved
- Branch must be up to date

### After Merging
- Immediately notify team if issues arise
- Monitor deployed changes
- Clean up branches
- Update related documentation

---

## Quick Commands Reference

```bash
# Setup
git clone <repo>
git config user.name "Name"
git config user.email "email@example.com"

# Daily Work
git status                                    # Check status
git add .                                     # Stage changes
git commit -m "#329: Fix header styling."     # Commit
git push origin feature/329-fix               # Push

# Branching
git branch                                    # List branches
git checkout -b feature/329-fix               # Create & switch
git checkout develop                          # Switch branch
git branch -d feature/329-fix                 # Delete branch

# Updating
git fetch origin                              # Fetch changes
git pull origin develop                       # Pull changes
git rebase origin/develop                     # Rebase on develop
git merge origin/develop                      # Merge develop

# Undoing
git reset HEAD file.php                       # Unstage file
git checkout file.php                         # Discard changes
git revert <hash>                             # Revert commit
git reset --hard HEAD~1                       # Undo last commit

# Advanced
git log --oneline -10                         # View commits
git diff                                      # See changes
git stash                                     # Save WIP
git cherry-pick <hash>                        # Apply commit
git rebase -i HEAD~3                          # Interactive rebase
```

