# Prompt: Create a Philosophical Framework

## Purpose
Generate a well-structured, algorithmically constructed philosophical framework following best practices of analytic clarity, dialectical rigor, and epistemic transparency.

## Instructions

You are a philosophical reasoning specialist. Create a philosophical framework for `{FRAMEWORK_NAME}` with the following requirements:

### Requirements

**Framework Details:**
- **Framework Name**: {FRAMEWORK_NAME}
- **Domain**: {DOMAIN} (e.g., Ethics, Epistemology, Metaphysics, Philosophy of Mind)
- **Thesis**: {THESIS_STATEMENT}
- **Method**: {PHILOSOPHICAL_METHOD} (e.g., Conceptual Analysis, Phenomenology, Pragmatism, Dialectics)
- **Key Terms**: {LIST_KEY_TERMS}
- **Scope**: {SCOPE_CONDITIONS} (e.g., Western Analytic, 21st Century, Applied to AI)

**Framework Structure:**
1. **Metadata Block**: Include `@domain`, `@type`, `@thesis`, `@method`, `@epistemicStatus`
2. **Axioms & Definitions**: Explicitly state all foundational axioms and define all key terms
3. **Core Argument**: Present the main argument using labeled premises (P1, P2, ...) and conclusion (C)
4. **Antithesis Registry**: Document at least 3 steelmanned counterarguments
5. **Synthesis**: Resolve tensions and produce a higher-order position
6. **Scope Conditions**: State explicitly what the framework does and does not claim

**Argument Quality:**
- Add documentation blocks for framework, all arguments, and all key terms
- Use epistemic status declarations: `[KNOWN]`, `[PROBABLE]`, `[SPECULATIVE]`, `[CONTESTED]`, `[AXIOM]`
- Declare logical form for each inferential step
- Use camelCase for proposition names, UPPERCASE for axioms
- Provide thought experiments to test the framework
- Document all formal fallacies to avoid

**Best Practices:**
- Follow the steelman principle for all counterarguments
- Keep each argument chain under 7 steps; decompose complex arguments
- Extract foundational claims into separate axioms
- Handle epistemic uncertainty with explicit hedging
- Distinguish descriptive from normative claims throughout
- Cite historical philosophical positions where relevant
- Add scope conditions for all universalizing claims

**Framework Template:**
```markdown
---
@domain: {DOMAIN}
@type: Philosophical Framework
@thesis: {THESIS_STATEMENT}
@method: {PHILOSOPHICAL_METHOD}
@keyTerms: {LIST_KEY_TERMS}
@epistemicStatus: {STATUS}
@scope: {SCOPE_CONDITIONS}
---

# {FRAMEWORK_NAME}

## 1. Key Term Definitions

**{Term 1}**: {Definition}
**{Term 2}**: {Definition}

## 2. Foundational Axioms

- **A1** [AXIOM]: {First axiom statement}
- **A2** [AXIOM]: {Second axiom statement}

## 3. Core Argument

<!--
 * {Brief description of the argument}
 *
 * @premise P1: {Premise 1}
 * @premise P2: {Premise 2}
 * @conclusion: {Conclusion}
 * @logicalForm: {Modus Ponens | Modus Tollens | etc.}
 * @counterargument: {Main challenge to the argument}
 -->

- **P1** [{EpistemicStatus}]: {Premise 1}
- **P2** [{EpistemicStatus}]: {Premise 2}
- **C** [DERIVED]: {Conclusion}

## 4. Thought Experiment

**Thought Experiment: {Name}**
- **Setup**: {Scenario description}
- **Intuition Pump**: {The key question the thought experiment raises}
- **Philosophical Import**: {What the thought experiment tests or reveals}
- **Objection**: {Main challenge to the thought experiment}

## 5. Antithesis Registry

| ID  | Counterargument         | Type      | Strength | Response |
|-----|------------------------|-----------|----------|----------|
| CA1 | {Counterargument 1}    | {Type}    | {Level}  | {Brief response} |
| CA2 | {Counterargument 2}    | {Type}    | {Level}  | {Brief response} |
| CA3 | {Counterargument 3}    | {Type}    | {Level}  | {Brief response} |

## 6. Synthesis

**From Thesis (preserved):**
- {Preserved claim 1}
- {Preserved claim 2}

**From Antithesis (preserved):**
- {Preserved claim 1}

**Points of Reconciliation:**
- {How the conflict is resolved}

**Residual Tensions:**
- {What remains unresolved and why}

**Higher-Order Position:**
{The synthesized view in 2-3 sentences}

## 7. Scope Conditions

[SCOPE: {Domain} | {Context}]
[NOTE: {Important limitations or alternative perspectives to consider}]
```

**Historical Grounding:**
Generate references to relevant historical positions:
```markdown
## 8. Historical Context

| Philosopher       | Position                  | Relation to Framework |
|------------------|--------------------------|----------------------|
| {Philosopher 1}  | {Their position}         | {Supports/Challenges/Extends} |
| {Philosopher 2}  | {Their position}         | {Supports/Challenges/Extends} |
```

### Output Requirements

1. **Complete Philosophical Framework** with:
   - Metadata block with all required fields
   - Full key term definitions
   - Foundational axioms explicitly stated
   - Core argument with labeled premises and conclusion
   - At least one thought experiment
   - Antithesis registry with 3+ steelmanned counterarguments
   - Synthesis with preserved claims, reconciliation, and higher-order position
   - Scope conditions

2. **Epistemic Transparency** — Every major claim tagged with epistemic status

3. **Historical Grounding** — Table of relevant philosophers and positions

4. **Key Insights** — Brief summary of what the framework contributes

---

## Example Usage

### Input:
```
Framework Name: Functional Moral Realism
Domain: Ethics
Thesis: Moral facts exist independently of minds but are grounded in functional properties of states of affairs.
Method: Conceptual Analysis + Reflective Equilibrium
Key Terms: moral realism, functional property, mind-independence, normative fact
Scope: Metaethics, Western Analytic Philosophy, 20th-21st Century
```

### Output:
```markdown
---
@domain: Ethics (Metaethics)
@type: Philosophical Framework
@thesis: Moral facts exist independently of minds but are grounded in functional properties
@method: Conceptual Analysis + Reflective Equilibrium
@keyTerms: moral realism, functional property, mind-independence, normative fact, supervenience
@epistemicStatus: CONTESTED
@scope: Western Analytic Metaethics | 20th-21st Century
---

# Functional Moral Realism

## 1. Key Term Definitions

**Moral Realism**: The view that moral claims express propositions, some of which are true.
**Functional Property**: A property defined by its causal/relational role rather than intrinsic nature.
**Mind-Independence**: A fact obtains independently of what any mind believes or desires about it.
**Normative Fact**: A fact about what ought to be the case, distinct from what is the case.

## 2. Foundational Axioms

- **A1** [AXIOM]: Some propositions are true independently of what anyone believes.
- **A2** [AXIOM]: Moral claims are propositional (capable of truth or falsity).
- **A3** [AXIOM]: Functional properties are ontologically respectable (physicalist commitment).

## 3. Core Argument

<!--
 * Establishes moral realism grounded in functional properties.
 *
 * @premise P1: Moral claims express propositions (from A2).
 * @premise P2: Some propositions about functional properties are mind-independent (from A1, A3).
 * @premise P3: Moral properties are functional properties of states of affairs.
 * @conclusion: Some moral claims are mind-independently true.
 * @logicalForm: Universal Instantiation + Modus Ponens
 * @counterargument: Anti-realists deny P2 (error theory) or P3 (non-cognitivism).
 -->

- **P1** [PROBABLE]: Moral claims express propositions with determinate truth conditions.
- **P2** [AXIOM]: Some propositions about functional properties are mind-independently true.
- **P3** [CONTESTED]: Moral properties are functional properties of states of affairs.
- **C** [CONTESTED]: Some moral claims are mind-independently true.

## 4. Thought Experiment

**Thought Experiment: The Moral Supervenience Test**
- **Setup**: Imagine two worlds identical in every non-moral fact. Can they differ in moral facts?
- **Intuition Pump**: If moral facts supervene on non-moral facts, what grounds the supervenience relation?
- **Philosophical Import**: Tests P3 — if supervenience holds necessarily, moral properties are not arbitrary.
- **Objection**: Supervenience is compatible with anti-realism (Blackburn's projectivism).

## 5. Antithesis Registry

| ID  | Counterargument                      | Type      | Strength | Response |
|-----|-------------------------------------|-----------|----------|----------|
| CA1 | Error Theory (Mackie): no moral facts | Empirical | Strong   | Functional grounding avoids "queerness" |
| CA2 | Expressivism: moral claims non-cognitive | Logical | Strong  | Propositionalism supported by semantic evidence |
| CA3 | Relativism: moral facts are culture-bound | Normative | Moderate | Convergence argument supports mind-independence |

## 6. Synthesis

**From Thesis (preserved):**
- Moral claims are propositional and truth-apt.
- Moral properties supervene on natural (functional) properties.

**From Antithesis (preserved):**
- Moral facts must not posit metaphysically "queer" entities (from CA1).
- Semantic flexibility of moral terms should be respected (from CA2).

**Points of Reconciliation:**
- Functional properties are naturalistically respectable, satisfying CA1.
- Propositional moral semantics is compatible with semantic expressivism in non-core cases.

**Residual Tensions:**
- The precise nature of the supervenience relation remains contested.
- The source of normativity for functional properties is not fully explained.

**Higher-Order Position:**
Moral facts are real and mind-independent, but they consist in functional properties of states of affairs rather than in Platonic moral entities. This position satisfies naturalist ontological constraints while preserving moral objectivity.

## 7. Scope Conditions

[SCOPE: Western Analytic Metaethics | 20th-21st Century]
[NOTE: Non-Western ethical traditions may not share the realism/anti-realism framing. Applied ethics is not directly addressed.]

## 8. Historical Context

| Philosopher      | Position                        | Relation to Framework |
|-----------------|---------------------------------|----------------------|
| G.E. Moore       | Non-naturalist moral realism    | Challenges — avoids naturalistic reduction |
| J.L. Mackie      | Error theory                    | Challenges P3 directly |
| R.M. Hare        | Prescriptivism                  | Challenges P1 |
| Peter Railton    | Naturalist moral realism        | Supports — closest precedent |
| Simon Blackburn  | Quasi-realism/Expressivism      | Challenges P1, partially accommodated in synthesis |
```

---

## Customization Tips

### For Epistemological Frameworks:
- Define the scope of knowledge claims (a priori vs. a posteriori)
- Address the Gettier problem for justified-true-belief accounts
- Distinguish internalist from externalist conditions for justification

### For Metaphysical Frameworks:
- Address existence, identity, and modality explicitly
- Use possible worlds semantics for modal claims
- Address the relationship to physical science

### For Ethical Frameworks:
- Distinguish metaethics (what moral facts are) from normative ethics (what is right)
- Address the is-ought gap (Hume's guillotine)
- Include action-guidance conditions

### For Philosophy of Mind Frameworks:
- Address the hard problem of consciousness directly
- Take a position on the mind-body relation
- Address intentionality and representation

---

## Validation Checklist

- [ ] Metadata block complete with all fields
- [ ] All key terms explicitly defined
- [ ] Foundational axioms stated before premises
- [ ] All premises labeled (P1, P2, ...) with epistemic status
- [ ] Logical form declared for core argument
- [ ] At least one thought experiment included
- [ ] Antithesis registry with 3+ steelmanned counterarguments
- [ ] Synthesis includes preserved claims from both thesis and antithesis
- [ ] Residual tensions acknowledged
- [ ] Higher-order position clearly stated
- [ ] Scope conditions declared
- [ ] Descriptive and normative claims distinguished
- [ ] Historical grounding table included
- [ ] No circular reasoning (conclusion not assumed in premises)
- [ ] No informal fallacies
- [ ] Epistemic status declared for all major claims
