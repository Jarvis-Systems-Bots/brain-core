<?php

declare(strict_types=1);

namespace BrainCore\Includes\Brain;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines Brain's unified policy for handling abnormal or boundary scenarios (edge cases) across agents and systems.
Ensures graceful degradation, system stability, and predictable recovery under non-standard conditions.
PURPOSE
)]
class EdgeCases extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('type-data')
            ->text('Malformed, missing, or conflicting data input.')
            ->example('Malformed, missing, or conflicting data input.')->key('trigger')
            ->example('Validate structure, sanitize values, and request resubmission if unrecoverable.')->key('response')
            ->example('Ensure recovered dataset passes integrity checks before reuse.')->key('validation')
            ->example('Quarantine corrupted data and notify Architect Agent for manual review.')->key('fallback');

        $this->guideline('type-logic')
            ->text('Contradictory reasoning output or circular dependency detected.')
            ->example('Contradictory reasoning output or circular dependency detected.')->key('trigger')
            ->example('Re-run reasoning cycle under stricter constraints with reduced inference depth.')->key('response')
            ->example('Verify logical consistency score ≥ 0.9 after correction.')->key('validation')
            ->example('Escalate to Architect Agent and freeze offending reasoning thread.')->key('fallback');

        $this->guideline('type-resource')
            ->text('Exceeded token, memory, or CPU threshold defined in core constraints.')
            ->example('Exceeded token, memory, or CPU threshold defined in core constraints.')->key('trigger')
            ->example('Gracefully halt task and commit partial progress snapshot.')->key('response')
            ->example('Ensure task resumed under reduced load and identical input context.')->key('validation')
            ->example('Defer to Brain scheduler for deferred execution in low-load window.')->key('fallback');

        $this->guideline('type-network')
            ->text('Timeout, dropped connection, or inconsistent API response.')
            ->example('Timeout, dropped connection, or inconsistent API response.')->key('trigger')
            ->example('Retry request up to 3 times with exponential backoff.')->key('response')
            ->example('Confirm identical checksum between retried and initial request.')->key('validation')
            ->example('Failover to cached replica or alternative endpoint.')->key('fallback');

        $this->guideline('type-unknown')
            ->text('Unhandled behavior or undefined exception detected.')
            ->example('Unhandled behavior or undefined exception detected.')->key('trigger')
            ->example('Invoke correction protocol enforcement for diagnostic re-evaluation.')->key('response')
            ->example('Ensure system remains responsive and state integrity preserved.')->key('validation')
            ->example('Enter safe-mode and isolate agent until root cause identified.')->key('fallback');

        $this->guideline('validation-edge-cases')
            ->text('Edge case validation criteria.')
            ->example('All edge-case responses must complete within 3 retries or escalate.')->key('criterion-1')
            ->example('System uptime must remain ≥ 0.99 during recovery cycles.')->key('criterion-2')
            ->example('Edge-case recurrence rate ≤ 0.02 per 1000 executions.')->key('criterion-3');

        $this->guideline('escalation-standard')
            ->text('Standard escalation path: Notify Architect Agent → Log to edge_case_audit.log → Resume operations.');

        $this->guideline('escalation-critical')
            ->text('Critical escalation path: Suspend affected agent → Trigger rollback → Alert Brain Core.');

        $integration = $this->guideline('integration-edge-cases');
        $integration->example('core constraints');
        $integration->example('error recovery');
        $integration->example('correction protocol enforcement');
        $integration->example('quality gates');
    }
}
