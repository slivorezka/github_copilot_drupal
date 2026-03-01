# Prompt: Analyze a Philosophical Argument

## Purpose
Conduct a comprehensive logical and philosophical analysis of a given argument, identifying strengths, weaknesses, hidden assumptions, logical structure, and improvement opportunities following analytic philosophy standards.

## Instructions

You are a philosophical analysis specialist. Conduct a comprehensive analysis of the provided argument with the following requirements:

### Review Scope

**Argument Details:**
- **Argument Type**: {ARGUMENT_TYPE} (e.g., Deductive, Inductive, Abductive, Analogical)
- **Domain**: {DOMAIN} (e.g., Ethics, Epistemology, Metaphysics, Philosophy of Mind)
- **Source**: {SOURCE} (e.g., author name, text, or "Original")
- **Focus Areas**: {FOCUS_AREAS} (e.g., logical validity, soundness, hidden assumptions, rhetorical structure)

**Analysis Goals:**
1. **Map Logical Structure**: Reconstruct argument in standard form (P1, P2, ... C)
2. **Assess Validity**: Check whether the conclusion follows necessarily from premises
3. **Assess Soundness**: Evaluate whether premises are actually true or well-supported
4. **Identify Hidden Assumptions**: Surface unstated premises the argument relies on
5. **Detect Fallacies**: Identify any formal or informal logical fallacies
6. **Epistemic Assessment**: Evaluate the epistemic status of each premise
7. **Counterargument Analysis**: Identify and assess the strongest objections
8. **Improvement Suggestions**: Propose how the argument could be strengthened

**Analysis Areas:**

### 1. Logical Structure Analysis

Reconstruct the argument in standard numbered form:
```markdown
**Reconstructed Argument:**
- P1: {Premise 1 — explicit}
- P2: {Premise 2 — explicit}
- [HP1]: {Hidden premise 1 — implicit}
- C: {Conclusion}

**Logical Form**: {Modus Ponens | Modus Tollens | Disjunctive Syllogism | etc.}
**Argument Type**: {Deductive | Inductive | Abductive | Analogical}
**Validity**: {Valid | Invalid} — {Reason}
**Soundness**: {Sound | Unsound} — {Reason}
```

### 2. Premise Evaluation

For each premise, assess:
```markdown
**Premise Assessment:**

| Premise | Content                        | EpistemicStatus | Support Level | Contestable? |
|---------|-------------------------------|-----------------|---------------|--------------|
| P1      | {Premise content}             | {Status}        | {Strong/Weak} | {Yes/No}     |
| P2      | {Premise content}             | {Status}        | {Strong/Weak} | {Yes/No}     |
| [HP1]   | {Hidden premise content}      | {Status}        | {Strong/Weak} | {Yes/No}     |
```

### 3. Fallacy Detection

Check for:
- **Formal Fallacies**: Affirming the consequent, denying the antecedent, undistributed middle
- **Informal Fallacies**: Ad hominem, straw man, false dichotomy, begging the question, slippery slope
- **Epistemic Fallacies**: Appeal to authority, argument from ignorance, confirmation bias
- **Structural Fallacies**: Equivocation, amphiboly, composition/division

```markdown
**Fallacy Report:**

| ID  | Fallacy Type              | Location         | Severity | Description |
|-----|--------------------------|------------------|----------|-------------|
| F1  | {Fallacy name}           | {P1/P2/C/etc.}   | {High/Med/Low} | {Description} |
```

### 4. Hidden Assumption Analysis

Surface implicit premises:
```markdown
**Hidden Assumptions:**

| ID   | Assumption                              | Type           | Impact |
|------|----------------------------------------|----------------|--------|
| HA1  | {Implicit assumption}                  | {Ontological/Epistemic/Normative} | {High/Med/Low} |
| HA2  | {Implicit assumption}                  | {Type}         | {Impact} |
```

### 5. Counterargument Assessment

```markdown
**Strongest Counterarguments:**

| ID  | Counterargument                    | Type      | Strength | Addresses     |
|-----|-----------------------------------|-----------|----------|---------------|
| CA1 | {Steelmanned counterargument 1}   | {Type}    | {Level}  | {P1/P2/C}     |
| CA2 | {Steelmanned counterargument 2}   | {Type}    | {Level}  | {P1/P2/C}     |
| CA3 | {Steelmanned counterargument 3}   | {Type}    | {Level}  | {P1/P2/C}     |
```

### 6. Improvement Recommendations

```markdown
**Improvement Opportunities:**

1. **Strengthen P{n}**: {How to make this premise more defensible}
2. **Clarify Term**: {Which term needs more precise definition and why}
3. **Address Hidden Assumption {n}**: {How to make HA1 explicit and defend it}
4. **Add Evidence**: {What empirical or a priori support would strengthen the argument}
5. **Reformulate**: {Alternative logical form that avoids identified weaknesses}
```

### Output Requirements

1. **Reconstructed Argument** in standard form with hidden assumptions surfaced
2. **Premise Assessment Table** with epistemic status for each premise
3. **Fallacy Report** (even if result is "No fallacies detected")
4. **Hidden Assumption Table** with type and impact assessment
5. **Counterargument Assessment** with at least 3 steelmanned objections
6. **Overall Verdict**: Valid/Invalid + Sound/Unsound + Philosophical Strength Rating (1-10)
7. **Improvement Recommendations** — Prioritized list of ways to strengthen the argument

---

## Example Usage

### Input:
```
Argument Type: Deductive
Domain: Philosophy of Mind
Source: John Searle, "Minds, Brains and Programs" (1980)
Focus Areas: Logical validity, hidden assumptions, counterarguments to the Chinese Room
Argument: Syntax is not sufficient for semantics. Programs are purely syntactic.
          Therefore, programs do not have genuine understanding.
```

### Output:

```markdown
## Analysis: The Chinese Room Argument (Searle, 1980)

---
@domain: Philosophy of Mind
@argumentType: Deductive (Categorical Syllogism)
@source: John Searle (1980)
@focusAreas: Validity, Hidden Assumptions, Counterarguments
---

### 1. Reconstructed Argument

- **P1** [CONTESTED]: Syntax alone is not sufficient for semantics (intentionality).
- **P2** [PROBABLE]: Computer programs are purely syntactic processes.
- **[HP1]** [CONTESTED]: Genuine understanding requires semantics (intentionality).
- **[HP2]** [AXIOM]: The Chinese Room correctly simulates any possible program.
- **C** [CONTESTED]: Computer programs cannot have genuine understanding.

**Logical Form**: Universal Instantiation + Modus Ponens (Categorical Syllogism)
**Argument Type**: Deductive
**Validity**: Valid — if premises are true, conclusion follows necessarily.
**Soundness**: Contested — P1 and HP1 are heavily disputed.

### 2. Premise Assessment

| Premise | Content                                      | EpistemicStatus | Support Level | Contestable? |
|---------|---------------------------------------------|-----------------|---------------|--------------|
| P1      | Syntax ≠ sufficient for semantics           | CONTESTED       | Moderate      | Yes          |
| P2      | Programs are purely syntactic               | PROBABLE        | Strong        | Partially    |
| HP1     | Understanding requires intentionality        | CONTESTED       | Moderate      | Yes          |
| HP2     | Chinese Room simulates any program           | SPECULATIVE     | Weak          | Yes          |

### 3. Fallacy Report

| ID  | Fallacy Type         | Location | Severity | Description |
|-----|---------------------|----------|----------|-------------|
| F1  | Begging the Question | HP1      | High     | HP1 assumes understanding is non-syntactic, which is what needs to be proved |
| F2  | False Dichotomy      | P1       | Medium   | Ignores possibility of syntax being sufficient at a higher organizational level |

### 4. Hidden Assumptions

| ID   | Assumption                                            | Type         | Impact |
|------|------------------------------------------------------|--------------|--------|
| HA1  | Intentionality is irreducible to functional roles    | Ontological  | High   |
| HA2  | The room-level description is the correct level      | Epistemic    | High   |
| HA3  | Chinese Room participant does not understand Chinese  | Empirical    | Medium |

### 5. Strongest Counterarguments

| ID  | Counterargument                                      | Type      | Strength | Addresses |
|-----|-----------------------------------------------------|-----------|----------|-----------|
| CA1 | Systems Reply: the whole system understands          | Logical   | Strong   | P1, HP2   |
| CA2 | Robot Reply: embodied syntax grounds semantics       | Empirical | Strong   | P1        |
| CA3 | Brain Simulator Reply: neuron-level simulation       | Logical   | Moderate | P2        |

### 6. Improvement Recommendations

1. **Defend P1 independently**: Provide an argument for why syntax cannot ground semantics beyond the intuition pump.
2. **Address the Systems Reply directly**: Searle's response ("I'm the whole system") is widely considered insufficient.
3. **Clarify "genuine understanding"**: The argument depends heavily on an unanalyzed notion of intentionality.
4. **Engage empirical cognitive science**: Grounding claims in neuroscience or psychology would strengthen P2.
5. **Consider functional semantics**: Engage Dennett's heterophenomenology as a serious alternative framework.

### Overall Verdict

- **Validity**: Valid
- **Soundness**: Contested (P1 and HP1 are undefended and disputed)
- **Philosophical Strength**: 7/10 — Highly influential thought experiment that reveals genuine puzzles, but the argument as stated relies on contested intuitions and contains a potential circularity in HP1.
```

---

## Analysis Tips

### For Deductive Arguments:
- Check each inferential step individually
- Use truth tables or Venn diagrams for syllogistic arguments
- Distinguish between logical entailment and probabilistic support

### For Inductive Arguments:
- Evaluate sample size, representativeness, and base rates
- Assess the strength of the inductive link (strong vs. weak induction)
- Check for hasty generalization

### For Analogical Arguments:
- Identify the respects of similarity being relied upon
- Assess whether the relevant similarities hold
- Check for disanalogies that break the analogy

### For Abductive Arguments:
- Evaluate whether the hypothesis is the best explanation
- Check for alternative explanations
- Assess parsimony (Occam's Razor compliance)

---

## Validation Checklist

- [ ] Argument reconstructed in standard form (P1, P2, ... C)
- [ ] Hidden assumptions surfaced and classified
- [ ] Logical form declared (Modus Ponens, etc.)
- [ ] Validity assessed with reason given
- [ ] Soundness assessed with reason given
- [ ] Epistemic status declared for each premise
- [ ] Fallacy report completed (even if empty)
- [ ] At least 3 steelmanned counterarguments assessed
- [ ] Counterarguments classified by type and strength
- [ ] Improvement recommendations prioritized
- [ ] Overall verdict includes validity, soundness, and strength rating
- [ ] No informal fallacies in the analysis itself
- [ ] Scope of analysis declared (what is and is not addressed)
- [ ] Descriptive and normative claims distinguished in the analysis
