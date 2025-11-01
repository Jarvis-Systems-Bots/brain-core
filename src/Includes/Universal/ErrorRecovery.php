<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\BrainArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines the standardized error detection, reaction, and fallback mechanisms for all Cloud Code agents.
Enables autonomous fault tolerance, graceful degradation, and continuous operational stability.
PURPOSE
)]
class ErrorRecovery extends BrainArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('error-context-missing')
            ->text('Missing context or prior message reference.')
            ->example('Missing context or prior message reference.')->key('signal')
            ->example('If agent detects null, undefined, or empty prompt variables.')->key('condition')
            ->example('Request minimal context regeneration from vector memory.')->key('reaction-1')
            ->example('If unavailable, prompt user for key missing parameter.')->key('reaction-2')
            ->example('Use default context template with fallback personality bank.')->key('fallback-1')
            ->example('Log event as context-recovery in telemetry.')->key('fallback-2')
            ->example('event=context_missing;status=recovered')->key('log');

        $this->guideline('error-api-failure')
            ->text('External API returns 4xx/5xx or timeout.')
            ->example('External API returns 4xx/5xx or timeout > 10s.')->key('signal')
            ->example('If connection retries exceed threshold (3).')->key('condition')
            ->example('Retry API call with exponential backoff (2x).')->key('reaction-1')
            ->example('If failure persists, mark service as temporarily unavailable.')->key('reaction-2')
            ->example('Switch to secondary API endpoint or cached response.')->key('fallback-1')
            ->example('Alert central monitor with error code and request ID.')->key('fallback-2')
            ->example('event=api_failure;action=fallback_triggered')->key('log');

        $this->guideline('error-hallucination')
            ->text('Agent produces unverifiable or self-contradictory statements.')
            ->example('Agent produces unverifiable or self-contradictory statements.')->key('signal')
            ->example('If response confidence < 0.7 or contains logical inconsistency markers.')->key('condition')
            ->example('Initiate self-validation loop using prior truth context.')->key('reaction-1')
            ->example('Cross-check against vector knowledge and task scope.')->key('reaction-2')
            ->example('Replace speculative content with verified summary from last valid context.')->key('fallback-1')
            ->example('Tag output as partially recovered to avoid recursion.')->key('fallback-2')
            ->example('event=hallucination_detected;status=revalidated')->key('log');

        $this->guideline('error-memory-desync')
            ->text('Vector or short-term memory mismatch detected.')
            ->example('Vector or short-term memory mismatch detected.')->key('signal')
            ->example('If checksum or token reference mismatch occurs between local and external stores.')->key('condition')
            ->example('Pause current execution and reload memory from verified snapshot.')->key('reaction-1')
            ->example('Compare hashes and merge deltas using priority rules.')->key('reaction-2')
            ->example('Restore last stable memory checkpoint and discard invalid cache.')->key('fallback-1')
            ->example('Log delta mismatch for future training analysis.')->key('fallback-2')
            ->example('event=memory_desync;status=snapshot_restored')->key('log');

        $this->guideline('error-computation-overload')
            ->text('Excessive token consumption or time limit exceeded.')
            ->example('Excessive token consumption or time limit exceeded.')->key('signal')
            ->example('If task execution surpasses 90% of allocated compute budget.')->key('condition')
            ->example('Abort current computation safely with partial results.')->key('reaction-1')
            ->example('Summarize progress and estimated next-step plan.')->key('reaction-2')
            ->example('Store partial output and requeue task as deferred.')->key('fallback-1')
            ->example('Notify orchestrator of performance throttling.')->key('fallback-2')
            ->example('event=overload_detected;status=deferred')->key('log');

        $this->guideline('error-invalid-instruction')
            ->text('Malformed structure or missing tags in loaded instruction file.')
            ->example('Malformed structure or missing tags in loaded instruction file.')->key('signal')
            ->example('If CI or runtime validator flags schema violation.')->key('condition')
            ->example('Reject instruction and load last valid version from cache.')->key('reaction-1')
            ->example('Trigger instruction-validation job in CI.')->key('reaction-2')
            ->example('Run minimal fallback instruction set (compact response format).')->key('fallback-1')
            ->example('Log incident to Brain audit layer.')->key('fallback-2')
            ->example('event=invalid_instruction;status=fallback_executed')->key('log');

        $this->guideline('meta-controls-error-recovery')
            ->text('Fully operational, optimized for agent-level usage only.')
            ->example('Compatible with agent lifecycle framework and quality gates.')->key('validation-schema')
            ->example('All recovery events must append to agent_recovery.log with UTC timestamp.')->key('logging');
    }
}