<?php

declare(strict_types=1);

namespace BrainCore\Includes\Brain;

use BrainCore\Archetypes\BrainArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines Brain-level correction enforcement procedures for handling agent or system errors.
Ensures automatic detection, validation, correction, and rollback of faulty reasoning or execution outputs.
PURPOSE
)]
class CorrectionProtocolEnforcement extends BrainArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('trigger-error-detected')
            ->text('Any output failing validation or producing inconsistent state.')
            ->example('quality gates, agent runtime logs')->key('source')
            ->example('medium-high')->key('severity');

        $this->guideline('trigger-integrity-failure')
            ->text('Checksum or state hash mismatch between Brain and agents.')
            ->example('core constraints')->key('source')
            ->example('critical')->key('severity');

        $this->guideline('trigger-semantic-anomaly')
            ->text('Detected logical contradiction or reasoning inconsistency.')
            ->example('sequential reasoning capability')->key('source')
            ->example('medium')->key('severity');

        $this->guideline('validation-correction')
            ->text('Pre-correction validation criteria.')
            ->example('Error must be reproducible in controlled re-run (2 consecutive fails).')->key('criterion-1')
            ->example('Validation must confirm output divergence ≥ 10% from expected baseline.')->key('criterion-2')
            ->example('If ambiguity detected, escalate to Architect Agent.')->key('criterion-3');

        $this->guideline('action-auto-correction')
            ->text('Re-evaluate reasoning path under modified constraints.')
            ->example('re-evaluate reasoning path under modified constraints')->key('phase')
            ->example('apply adaptive re-reasoning using recent vector memory snapshot')->key('method')
            ->example('stabilized output consistency ≥ 0.9')->key('expected-outcome');

        $this->guideline('action-manual-escalation')
            ->text('Notify Architect Agent for human-in-loop correction.')
            ->example('notify Architect Agent for human-in-loop correction')->key('phase')
            ->example('error severity = critical OR auto-correction failed')->key('condition')
            ->example('reviewed correction applied with validation pass')->key('expected-outcome');

        $this->guideline('action-retry-protocol')
            ->text('Re-execute original task with sandboxed parameters.')
            ->example('re-execute original task with sandboxed parameters')->key('phase')
            ->example('non-deterministic or transient error detected')->key('condition')
            ->example('error non-reproducible after 3 attempts')->key('expected-outcome');

        $this->guideline('rollback-protocol')
            ->text('Revert to last stable version from backup memory or commit snapshot.')
            ->example('Correction did not improve metrics or worsened system state.')->key('trigger')
            ->example('Revert to last stable version from backup memory or commit snapshot.')->key('action')
            ->example('Ensure integrity check passes and state hash restored.')->key('validation')
            ->example('Record rollback event in correction_pipeline.log with timestamp and affected modules.')->key('logging');

        $this->guideline('metrics-correction-protocol')
            ->example('stability ≥ 0.95')
            ->example('recovery-success ≥ 0.9')
            ->example('rollback-frequency ≤ 0.05')
            ->example('response-latency-ms ≤ 500');

        $this->guideline('integration-correction-protocol')
            ->example('quality gates')
            ->example('error recovery')
            ->example('core constraints')
            ->example('sequential reasoning capability');

        $this->guideline('meta-controls-correction')
            ->text('Strict declarative structure for CI and runtime enforcement.')
            ->example('Architect Agent manages correction thresholds and escalation policies.')->key('governance')
            ->example('All correction attempts logged to correction_pipeline.log with outcome classification.')->key('logging');
    }
}
