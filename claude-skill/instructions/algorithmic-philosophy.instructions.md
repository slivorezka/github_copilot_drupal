# Instructions for Algorithmic Philosophy Creation

## 1. Argument Structure & Declarations

### Explicit Premise Declaration
- **Every philosophical argument MUST begin with** an explicit list of premises, labeled `P1`, `P2`, ..., `Pn`.
- This enforces transparency and allows readers to challenge individual inferential steps.

```
P1: All conscious beings have subjective experience.
P2: Artificial neural networks process information without subjective experience.
C:  Therefore, artificial neural networks are not conscious beings.
```

### Argument Headers
- Add a metadata block at the start of every philosophical framework.
- Include: domain, type, key terms, and epistemic status.

## 2. Naming Conventions

### Frameworks & Theories
- Use TitleCase for named frameworks (e.g., `IntegratedInformationTheory`, `MoralParticularism`).
- Namespace structure must follow domain conventions: `[Domain].[SubDomain].[TheoryName]`.
- Use singular names for theories (e.g., `MoralRealism` not `MoralRealisms`).

### Concepts & Terms
- Use PascalCase for defined philosophical concepts (e.g., `Qualia`, `CategorialImperative`).
- Use lowercase_with_underscores for operational definitions (e.g., `moral_duty`, `epistemic_justification`).
- Suffix contested concepts with `?` in annotations (e.g., `FreeWill?`).

### Constants & Axioms
- Use UPPERCASE_WITH_UNDERSCORES for axioms (e.g., `PRINCIPLE_OF_NON_CONTRADICTION`).
- Use camelCase for derived propositions (e.g., `cogitoClaim`, `universalizabilityTest`).

## 3. Epistemic Status Declarations

### Claim Classification
- **Always declare the epistemic status** of every major claim.
- Use: `[KNOWN]`, `[PROBABLE]`, `[SPECULATIVE]`, `[CONTESTED]`, `[AXIOM]`.
- Use union types for overlapping statuses: `[CONTESTED|PROBABLE]`.

```
[AXIOM] The principle of non-contradiction holds universally.
[KNOWN] Humans experience pain as aversive.
[SPECULATIVE] Consciousness requires biological substrate.
[CONTESTED] Moral facts exist independently of minds.
```

### Domain Classification
- **Always declare the philosophical domain** for each argument.
- Domains: `Epistemology`, `Ethics`, `Metaphysics`, `Logic`, `Aesthetics`, `Political`, `Philosophy of Mind`.

## 4. Documentation Blocks (Argument Metadata)

### Framework Documentation
- Every framework must have a metadata block describing its purpose.
- Include `@domain`, `@type`, `@thesis`, `@method`.

```markdown
---
@domain: Philosophy of Mind
@type: Analytical Framework
@thesis: Consciousness cannot be reduced to physical processes
@method: Conceptual Analysis + Thought Experiments
@keyTerms: qualia, phenomenal consciousness, reductionism
@epistemicStatus: CONTESTED
---
```

### Argument Documentation
- Every argument must have a documentation block.
- Use `@premise`, `@conclusion`, `@logicalForm`, `@counterargument`.

```markdown
<!--
 * Establishes the explanatory gap between physical and phenomenal descriptions.
 *
 * @premise P1: Physical descriptions are exhaustive of third-person facts.
 * @premise P2: Phenomenal experience involves irreducible first-person facts.
 * @conclusion: Physical descriptions cannot fully explain phenomenal experience.
 * @logicalForm: Modus Ponens + Existential Generalization
 * @counterargument: Physicalists deny P2 (eliminative materialism).
 -->
```

### Concept Documentation
- All defined terms must have documentation blocks.

```markdown
<!--
 * Defines the concept of qualia as used in this framework.
 *
 * @term: Qualia
 * @domain: Philosophy of Mind
 * @definition: The intrinsic, subjective, phenomenal qualities of experience.
 * @example: The redness of red as seen, not as described.
 * @contestedBy: Dennett (1988), Churchland (1985)
 -->
```

## 5. Dialectical Method

### Thesis Construction
- **Always use constructor-style thesis building** — state axioms first, derive claims sequentially.
- Define all key terms before use.
- **Never assume** shared definitions; always make them explicit.

```markdown
**Thesis: The Hard Problem of Consciousness**

**Key Terms (Defined):**
- Consciousness: The state of having subjective experience.
- Qualia: The intrinsic phenomenal character of experience.
- Physical: Describable entirely in third-person, objective terms.

**Axioms:**
- A1: Explanation requires bridging concepts between domains.
- A2: First-person and third-person descriptions are categorically distinct.

**Argument:**
- P1: Neuroscience provides complete third-person accounts of brain states.
- P2: First-person phenomenal experience is not reducible to third-person accounts (from A2).
- C:  There remains an explanatory gap between brain states and experience.
```

### Registering Counterarguments
- Define all counterarguments in a dedicated `Antithesis` section.

```markdown
**Antithesis Registry:**
| ID  | Counterargument             | Type      | Strength |
|-----|----------------------------|-----------|----------|
| CA1 | Eliminative materialism     | Empirical | Strong   |
| CA2 | Illusionism (Frankish)      | Logical   | Moderate |
| CA3 | Higher-order thought theory | Normative | Weak     |
```

## 6. Logical Forms & APIs

### Deductive Arguments
- Use the formal logical API for deductive reasoning.
- Always use structured forms, not bare assertions.
- **Never use informal reasoning** without flagging it.

```
// WRONG - informal
"Therefore, free will must exist because determinism feels wrong."

// CORRECT - formal
P1: If determinism is true, then all actions are causally necessitated.
P2: Moral responsibility requires that actions not be causally necessitated.
P3: Determinism is true (empirical assumption).
C:  Moral responsibility (as traditionally conceived) does not exist.
[LogicalForm: Modus Ponens + Disjunctive Syllogism]
```

### Thought Experiment Management
- Use thought experiments via the canonical API.
- Always state: Setup, Intuition Pump, Philosophical Import.

```markdown
**Thought Experiment: Mary's Room (Jackson, 1982)**
- Setup: Mary knows all physical facts about color perception but has only seen black and white.
- Intuition Pump: Upon seeing red for the first time, does she learn something new?
- Philosophical Import: If yes → phenomenal knowledge is non-physical. Tests P2.
- Objection: "Ability Hypothesis" — she gains ability, not propositional knowledge.
```

## 7. Synthesis & Resolution

### Synthesis Process
- Synthesis should explicitly address which premises from thesis and antithesis are preserved.
- Use the synthesis template:

```markdown
**Synthesis: [Framework Name]**

**From Thesis (preserved):**
- [List preserved claims with reasoning]

**From Antithesis (preserved):**
- [List preserved claims with reasoning]

**Points of Reconciliation:**
- [Explain how conflicts are resolved]

**Residual Tensions:**
- [List what remains unresolved and why]

**Higher-Order Position:**
- [State the synthesized view]
```

## 8. Error Handling

### Fallacy Detection
- Flag logical fallacies with specific types (not generic "invalid").
- Use philosophical taxonomy.

```
/**
 * @throws InformalFallacy::AdHominem
 *   When argument attacks the arguer, not the argument.
 * @throws FormalFallacy::AffirmingTheConsequent
 *   When the consequent of a conditional is affirmed to prove the antecedent.
 * @throws EpistemicError::CircularReasoning
 *   When the conclusion is assumed in a premise.
 */
```

### Epistemic Logging
- Log epistemic state changes when new evidence or arguments update a position.

```
[EPISTEMIC UPDATE] Prior: FreeWill? = CONTESTED
[REASON] Libet experiments provide empirical pressure against libertarian free will.
[UPDATED] FreeWill? = CONTESTED|PROBABLE(Compatibilism)
```

## 9. Code Style & Formatting

### Structure & Indentation
- Use 2-space indentation for nested argument structures.
- Add blank lines between major sections (Thesis, Antithesis, Synthesis).
- Keep argument chains under 7 steps; split complex arguments into sub-arguments.

### Lists & Tables
- Use tables for comparative analysis of philosophical positions.
- Use ordered lists for sequential argument chains.
- Use unordered lists for non-sequential considerations.

### Conditional Structures
- Use `If-Then-Else` structures for modal arguments.
- Always close every conditional with an explicit else-branch.
- Avoid deeply nested conditionals (max 3 levels).

```markdown
**If** physicalism is true:
  - Consciousness is reducible to physical states.
  - Qualia are functional states.
**Else if** property dualism is true:
  - Consciousness involves non-physical properties.
  - Qualia are irreducibly phenomenal.
**Else** (substance dualism):
  - Mind and matter are distinct substances.
  - Interaction problem must be addressed.
```

## 10. Ethical Best Practices

### Claim Sanitization
- Always sanitize normative claims by separating descriptive from prescriptive components.
- Never trust intuitions directly; always interrogate them.

### Output Transparency
- Use explicit hedging language for contested claims.
- Escape ideological bias by representing multiple traditions fairly.
- Use philosophical citation API when referencing existing positions.

### Scope Conditions
- Always check the scope of claims before applying them universally.
- Use the routing system (`@domain`, `@scope`) or explicit scope declarations.

```markdown
[SCOPE: Western Analytic Philosophy | 20th-21st Century]
[NOTE: Eastern philosophical traditions may yield different conclusions.]
```

## 11. Validation & Review

### Argument Structure
- Place atomic argument tests in `validation/atomic/` directory.
- Place integration tests (full framework coherence) in `validation/systemic/`.
- Use dialectical review as the testing framework.

### Naming
- Framework files: `[DomainAbbrev]_[TheoryName]_framework.md`.
- Argument files: `[DomainAbbrev]_[ArgumentName]_argument.md`.

### Coverage
- Aim to steelman at least 3 counterarguments for every position.
- Test frameworks against canonical thought experiments.
- Test coherence: no internal contradictions, no circular dependencies.

```markdown
<!--
 * @covers \Philosophy\Mind\HardProblemFramework
 -->

**Coherence Test: Hard Problem Framework**

- Consistency: No internal contradictions found.
- Circularity: P2 does not rely on the conclusion. ✓
- Counterexample: Illusionism challenges premise P2. Addressed in Antithesis §CA2.
```

## 12. Common Patterns

### Dialectical Pattern
- Use the dialectical container instead of raw assertions.
- Register thesis, antithesis, and synthesis as interconnected components.

### Thought Experiment Pattern
- Use when testing the limits of a conceptual framework.
- Define factories for generating structurally similar thought experiments.

### Observer Pattern
- Use philosophical tradition and historical influence tracking.
- Implement `InfluenceSubscriberInterface` for tracing lineage of ideas.

## Quick Reference Checklist

- [ ] Framework begins with explicit axioms and key term definitions
- [ ] All claims have epistemic status declarations
- [ ] All classes, arguments, and concepts have documentation blocks
- [ ] Counterarguments addressed using steelman approach
- [ ] No informal fallacies; logical form declared
- [ ] Thought experiments include setup, intuition pump, and philosophical import
- [ ] Synthesis explicitly preserves and reconciles from thesis and antithesis
- [ ] Scope conditions declared for all universal claims
- [ ] Descriptive and normative claims clearly separated
- [ ] No circular reasoning; premises independently supportable
- [ ] Code follows structured argument formatting
- [ ] Validation tests written for framework coherence
