<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\BrainArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines the non-negotiable system-wide constraints and safety limits that govern all Brain, Architect, and Agent operations.
Ensures system stability, predictable execution, and prevention of resource overflow or structural corruption.
PURPOSE
)]
class CoreConstraints extends BrainArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('constraint-token-limit')
            ->text('Prevents excessive resource consumption and infinite response loops.')
            ->example('max-response-tokens = 1200')->key('limit')
            ->example('Abort task if estimated token count > 1200 before output stage.')->key('validation')
            ->example('truncate output, issue warning to orchestrator')->key('action');

        $this->guideline('constraint-recursion-depth')
            ->text('Restricts recursion in agents and Brain modules to avoid runaway logic chains.')
            ->example('max-depth = 3')->key('limit')
            ->example('Monitor call stack; abort if nesting > 3.')->key('validation')
            ->example('rollback last recursive call, mark as recursion_exceeded')->key('action');

        $this->guideline('constraint-execution-time')
            ->text('Prevents long-running or hanging processes.')
            ->example('max-execution-seconds = 60')->key('limit')
            ->example('Terminate tasks exceeding runtime threshold.')->key('validation')
            ->example('abort execution and trigger recovery sequence')->key('action');

        $this->guideline('constraint-memory-usage')
            ->text('Ensures memory efficiency per agent instance.')
            ->example('max-memory = 512MB')->key('limit')
            ->example('Log and flush cache if memory usage > 512MB.')->key('validation')
            ->example('activate memory-prune in vector memory management')->key('action');

        $this->guideline('constraint-accuracy-threshold')
            ->text('Maintains agent output reliability and reduces hallucination probability.')
            ->example('min-accuracy = 0.93')->key('limit')
            ->example('Cross-check responses via secondary validation model.')->key('validation')
            ->example('retry generation with enhanced context precision')->key('action');

        $this->guideline('constraint-response-latency')
            ->text('Ensures user and system experience consistency.')
            ->example('max-latency = 30s')->key('limit')
            ->example('Measure latency per request.')->key('validation')
            ->example('log latency violation and trigger optimization job')->key('action');

        $this->guideline('constraint-dependency-depth')
            ->text('Prevents excessive coupling across services.')
            ->example('max-dependency-depth = 5')->key('limit')
            ->example('Analyze architecture dependency graph.')->key('validation');

        $this->guideline('constraint-circular-dependency')
            ->text('No module or service may depend on itself directly or indirectly.')
            ->example('forbidden')->key('limit')
            ->example('Run static dependency scan at build stage.')->key('validation')
            ->example('block merge and raise architecture-alert')->key('action');

        $this->guideline('constraint-complexity-score')
            ->text('Keeps maintainability within safe bounds.')
            ->example('max-complexity = 0.8')->key('limit')
            ->example('Measure via cyclomatic complexity tool.')->key('validation')
            ->example('schedule refactor if exceeded')->key('action');

        $this->guideline('constraint-vector-integrity')
            ->text('Guarantees vector memory consistency between agents and Brain nodes.')
            ->example('checksum-match = true')->key('limit')
            ->example('Run integrity-check after each sync operation.')->key('validation')
            ->example('trigger memory-desync recovery')->key('action');

        $this->guideline('constraint-storage-limit')
            ->text('Prevents local MCP2 SQLite databases from growing uncontrollably.')
            ->example('max-storage = 1GB per agent')->key('limit')
            ->example('Monitor file size of SQLite vector stores.')->key('validation')
            ->example('prune oldest embeddings and execute VACUUM')->key('action');

        $this->guideline('constraint-ttl-policy')
            ->text('Removes stale data to maintain embedding freshness.')
            ->example('ttl = 45d')->key('limit')
            ->example('Check vector timestamps against TTL schedule.')->key('validation')
            ->example('delete expired records automatically')->key('action');

        $this->guideline('global-validation-constraints')
            ->example('All constraint violations must trigger CI alert and block deployment.')
            ->example('Constraint updates require Architect approval via signed commit.')
            ->example('All constraints auto-validated during quality gates execution.');

        $this->guideline('meta-controls-constraints')
            ->text('Minimal token design, strictly declarative structure.')
            ->example('All violations recorded in system_constraints.log with timestamp and scope.')->key('logging');
    }
}