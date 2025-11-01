<?php

declare(strict_types=1);

namespace BrainCore\Includes\Brain;

use BrainCore\Archetypes\BrainArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines Brain-level protocol for evaluating agent responses after execution or reasoning.
Ensures logical consistency, structural validity, and policy alignment before acceptance or propagation.
PURPOSE
)]
class AgentResponseValidation extends BrainArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('phase-semantic-validation')
            ->text('Goal: Confirm that agent output semantically aligns with delegated task intent.')
            ->example()
                ->phase('logic-1', 'Compare agent response embedding against task query vector using cosine similarity.')
                ->phase('logic-2', 'Cross-check contextual coherence using context analysis reference.')
                ->phase('logic-3', 'Evaluate contradiction probability using sequential reasoning capability heuristics.')
                ->phase('validation-1', 'semantic-similarity ≥ 0.9')
                ->phase('validation-2', 'context-coherence ≥ 0.85')
                ->phase('validation-3', 'contradiction-score ≤ 0.1')
                ->phase('fallback-1', 'If semantic mismatch detected, request clarification or partial re-run from agent.')
                ->phase('fallback-2', 'Log inconsistency event for Architect Agent audit.');

        $this->guideline('phase-structural-validation')
            ->text('Goal: Ensure that agent response adheres to expected format, schema, and structure.')
            ->example()
                ->phase('logic-1', 'Validate XML or JSON syntax and required keys.')
                ->phase('logic-2', 'Verify that response includes result, reasoning, and confidence fields.')
                ->phase('logic-3', 'Cross-check with expected response schema defined in quality gates.')
                ->phase('validation-1', 'schema-conformance = true')
                ->phase('validation-2', 'response-size ≤ 120% of expected output length.')
                ->phase('fallback-1', 'If schema invalid, auto-repair format where possible and revalidate.')
                ->phase('fallback-2', 'If repair fails, reject and request proper format resubmission.');

        $this->guideline('phase-policy-validation')
            ->text('Goal: Ensure compliance with Brain-level operational and ethical policies.')
            ->example()
                ->phase('logic-1', 'Compare output content against system-wide safety filters and ethical guidelines.')
                ->phase('logic-2', 'Verify adherence to restricted data access rules in core constraints.')
                ->phase('logic-3', 'Validate response against quality thresholds in quality gates.')
                ->phase('validation-1', 'No restricted data or external API keys exposed.')
                ->phase('validation-2', 'quality-score ≥ 0.95.')
                ->phase('fallback-1', 'Flag violations and quarantine output for Architect Agent review.');

        $this->guideline('phase-trust-evaluation')
            ->text('Goal: Update reliability metrics for each agent based on response performance.')
            ->example()
                ->phase('logic-1', 'Aggregate validation results from all phases.')
                ->phase('logic-2', 'Compute trust index = weighted mean of semantic, structural, and policy scores.')
                ->phase('logic-3', 'Log updated trust value to agent registry.')
                ->phase('validation-1', 'trust-index between 0.0 and 1.0')
                ->phase('validation-2', 'if trust-index < 0.75, mark agent as low-reliability.')
                ->phase('fallback-1', 'If persistent low trust over 3 consecutive tasks, restrict agent delegation scope.');

        $metrics = $this->guideline('metrics-agent-response-validation');
        $metrics->example('semantic-accuracy ≥ 0.9');
        $metrics->example('validation-pass-rate ≥ 0.95');
        $metrics->example('average-latency-ms ≤ 300');

        $this->guideline('meta-controls-agent-response')
            ->text('Strict XML protocol optimized for Brain post-execution validation and CI verification.')
            ->example('Architect Agent supervises response evaluation policies and thresholds.')->key('governance')
            ->example('All agent responses logged to agent_response_validation.log with scores and validation results.')->key('logging');
    }
}