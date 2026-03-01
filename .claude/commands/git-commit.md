Generate a properly formatted Git commit message for the current changes based on the active branch name.

Context: $ARGUMENTS

## Rules

### Branch-Based Commit Message Format

The commit message format depends on the current Git branch:

**For `feature/*` and `bugfix/*` branches:**
- Extract the ticket number from the branch name
- Format: `#<TICKET_NUMBER>: <Description>.`
- Example:
  - Branch: `feature/329-search-styling`
  - Commit: `#329: Update search feature styling.`

**For `hotfix/*` branches:**
- Format: `#<TICKET_NUMBER>: <Description>.`
- If no ticket number in branch name, use a descriptive message

**For `docs/*` branches:**
- Format: `#<TICKET_NUMBER>: <Description>.` (if ticket exists)
- Or: `Update <subject> documentation.`

**For `refactor/*` branches:**
- Format: `#<TICKET_NUMBER>: <Description>.` (if ticket exists)
- Or: `Refactor <subject>.`

### General Rules

1. **Capitalize** the first letter of the description
2. **End with period** (full stop)
3. **Max 72 characters** for the first line
4. **Present tense imperative**: "Add", "Fix", "Update", "Remove", "Refactor"
5. **One logical change** per commit

### Good Examples

```
#329: Update search feature styling.
#445: Add user profile avatar upload.
#456: Fix mobile menu alignment issue.
#789: Remove deprecated helper functions.
#102: Refactor database queries to use batch loading.
```

### Bad Examples (avoid these)

```
updates the search styling          # No ticket, no period, lowercase verb
Fixed header on mobile              # Past tense, no ticket
#329 search styling updates         # Missing colon, no period
329: search styling                 # Missing hash, no capitalization, no period
```

### Extended Format (for complex changes)

If the changes are complex, generate an extended message:

```
#329: Add search feature styling.

- Update search input styling for better UX
- Improve search results display
- Add loading indicator
- Fix accessibility issues with form labels
```

## Instructions

1. Check the current Git branch name
2. Extract the ticket number if present
3. Analyze the staged or recent changes
4. Generate one or more commit message options following the format above
5. If the context describes multiple logical changes, suggest splitting into separate commits

