# Claude Skills

This file documents the available Claude skills in this repository.

## Available Skills

### Algorithmic Philosophy Creation

**Location**: `claude-skill/`

A structured skill for systematically creating and analyzing philosophical frameworks using algorithmic, step-by-step methods.

| File | Purpose |
|------|---------|
| `claude-skill/algorithmic-philosophy-creation.md` | Main overview — methodology, architecture, dialectical process |
| `claude-skill/instructions/algorithmic-philosophy.instructions.md` | Detailed rules for argument structure, epistemic declarations, documentation, and synthesis patterns |
| `claude-skill/prompts/create-philosophy-framework.prompt.md` | Prompt template for generating a full philosophical framework |
| `claude-skill/prompts/analyze-philosophical-argument.prompt.md` | Prompt template for conducting a structured philosophical argument analysis |

## Usage

Invoke a prompt by referencing its file and filling in the `{PLACEHOLDER}` fields:

```
Domain: Ethics
Thesis: Moral facts exist independently of minds.
Method: Conceptual Analysis
Key Terms: moral realism, mind-independence, normative fact
```
