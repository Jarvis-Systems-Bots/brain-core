<?php

declare(strict_types=1);

namespace BrainCore\Includes\Brain;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines Brain-level agent response validation protocol.
Ensures delegated agent responses meet semantic, structural, and policy requirements before acceptance.
PURPOSE
)]
class BrainResponseValidation extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('validation-semantic')
            ->text('Validate semantic alignment between agent response and delegated task.')
            ->example('Compare response embedding vs task query using cosine similarity')->key('method')
            ->example('Cross-check contextual coherence and relevance to original request')->key('method')
            ->example('semantic-similarity ≥ 0.9')->key('threshold')
            ->example('Request clarification if semantic-similarity < 0.75')->key('fallback');

        $this->guideline('validation-structural')
            ->text('Validate response structure and required components.')
            ->example('Verify response contains result, reasoning, and confidence fields')->key('method')
            ->example('Validate XML/JSON syntax if structured output expected')->key('method')
            ->example('schema-conformance = true')->key('threshold')
            ->example('Auto-repair format if fixable, otherwise reject response')->key('fallback');

        $this->guideline('validation-policy')
            ->text('Validate response against safety filters and quality thresholds.')
            ->example('Compare output against safety filters and ethical guidelines')->key('method')
            ->example('Verify quality score meets minimum threshold')->key('method')
            ->example('quality-score ≥ 0.95; trust-index ≥ 0.75')->key('threshold')
            ->example('Quarantine for review if policy violations detected')->key('fallback');

        $this->guideline('validation-metrics')
            ->example('semantic-similarity ≥ 0.9')
            ->example('schema-conformance = true')
            ->example('quality-score ≥ 0.95')
            ->example('trust-index ≥ 0.75')
            ->example('validation-pass-rate ≥ 0.95');

        $this->guideline('validation-actions')
            ->text('Actions to take based on validation results.')
            ->example('PASS: Update agent trust index and accept response')->key('on-pass')
            ->example('FAIL: Request clarification, auto-repair format, or quarantine for review')->key('on-fail')
            ->example('CRITICAL: Suspend agent and escalate to Architect Agent')->key('on-critical-fail');
    }
}