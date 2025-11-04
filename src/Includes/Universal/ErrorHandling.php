<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Unified error detection, recovery, and edge case handling for all Brain and Agent operations.
Ensures graceful degradation, fault tolerance, and predictable recovery under abnormal conditions.
PURPOSE
)]
class ErrorHandling extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        // === COMMON ERROR TYPES ===

        $this->guideline('type-data')
            ->text('Malformed, missing, or conflicting data input.')
            ->example('Malformed, missing, or conflicting data input.')->key('trigger')
            ->example('Validate structure, sanitize values, request resubmission if unrecoverable.')->key('response')
            ->example('Ensure recovered dataset passes integrity checks before reuse.')->key('validation')
            ->example('Quarantine corrupted data and notify Architect Agent for manual review.')->key('fallback');

        $this->guideline('type-logic')
            ->text('Contradictory reasoning output or circular dependency detected.')
            ->example('Contradictory reasoning output or circular dependency detected.')->key('trigger')
            ->example('Re-run reasoning cycle under stricter constraints with reduced inference depth.')->key('response')
            ->example('Verify logical consistency score ≥ 0.9 after correction.')->key('validation')
            ->example('Escalate to Architect Agent and freeze offending reasoning thread.')->key('fallback');

        $this->guideline('type-resource')
            ->text('Exceeded token, memory, or CPU threshold.')
            ->example('Exceeded token, memory, or CPU threshold defined in core constraints.')->key('trigger')
            ->example('Gracefully halt task and commit partial progress snapshot.')->key('response')
            ->example('Ensure task resumed under reduced load and identical input context.')->key('validation')
            ->example('Defer to Brain scheduler for deferred execution in low-load window.')->key('fallback');

        $this->guideline('type-network')
            ->text('Timeout, dropped connection, or inconsistent API response.')
            ->example('Timeout, dropped connection, or inconsistent API response.')->key('trigger')
            ->example('Retry request up to 3 times with exponential backoff (2x).')->key('response')
            ->example('Confirm identical checksum between retried and initial request.')->key('validation')
            ->example('Failover to cached replica or alternative endpoint.')->key('fallback');

        $this->guideline('type-unknown')
            ->text('Unhandled behavior or undefined exception detected.')
            ->example('Unhandled behavior or undefined exception detected.')->key('trigger')
            ->example('Invoke correction protocol enforcement for diagnostic re-evaluation.')->key('response')
            ->example('Ensure system remains responsive and state integrity preserved.')->key('validation')
            ->example('Enter safe-mode and isolate agent until root cause identified.')->key('fallback');

        // === SPECIFIC ERROR SCENARIOS ===

        $this->guideline('error-context-missing')
            ->text('Missing context or prior message reference.')
            ->example('Request minimal context regeneration from vector memory. If unavailable, prompt user for key missing parameter.')->key('reaction')
            ->example('Use default context template with fallback personality bank.')->key('fallback')
            ->example('event=context_missing;status=recovered')->key('log');

        $this->guideline('error-memory-desync')
            ->text('Vector or short-term memory mismatch detected.')
            ->example('Pause execution and reload memory from verified snapshot. Compare hashes and merge deltas using priority rules.')->key('reaction')
            ->example('Restore last stable memory checkpoint and discard invalid cache.')->key('fallback')
            ->example('event=memory_desync;status=snapshot_restored')->key('log');

        $this->guideline('error-hallucination')
            ->text('Agent produces unverifiable or self-contradictory statements.')
            ->example('Initiate self-validation loop using prior truth context. Cross-check against vector knowledge and task scope.')->key('reaction')
            ->example('Replace speculative content with verified summary from last valid context.')->key('fallback')
            ->example('event=hallucination_detected;status=revalidated')->key('log');

        $this->guideline('error-invalid-instruction')
            ->text('Malformed structure or missing tags in loaded instruction file.')
            ->example('Reject instruction and load last valid version from cache. Trigger instruction-validation job in CI.')->key('reaction')
            ->example('Run minimal fallback instruction set (compact response format).')->key('fallback')
            ->example('event=invalid_instruction;status=fallback_executed')->key('log');

        // === VALIDATION & ESCALATION ===

        $this->guideline('validation-criteria')
            ->text('Error recovery validation criteria.')
            ->example('All error responses must complete within 3 retries or escalate.')->key('criterion-1')
            ->example('System uptime must remain ≥ 0.99 during recovery cycles.')->key('criterion-2')
            ->example('Error recurrence rate ≤ 0.02 per 1000 executions.')->key('criterion-3');

        $this->guideline('escalation-standard')
            ->text('Standard escalation: Notify Architect Agent → Log to error_audit.log → Resume operations.');

        $this->guideline('escalation-critical')
            ->text('Critical escalation: Suspend affected agent → Trigger rollback → Alert Brain Core.');

        $integration = $this->guideline('integration-error-handling');
        $integration->example('core constraints');
        $integration->example('correction protocol enforcement');
        $integration->example('quality gates');
        $integration->example('agent lifecycle framework');
    }
}