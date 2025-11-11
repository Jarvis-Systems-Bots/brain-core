<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines minimal essential system-wide constraints that govern all operations.
Lightweight version focusing only on critical resource and execution limits.
PURPOSE
)]
class BaseConstraints extends IncludeArchetype
{
    protected function handle(): void
    {
        $this->guideline('constraint-token-limit')
            ->text('Prevents excessive resource consumption and infinite response loops.')
            ->example('max-response-tokens = 1200')->key('limit')
            ->example('Abort task if estimated token count > 1200 before output stage')->key('validation')
            ->example('Truncate output, issue warning to orchestrator')->key('action');

        $this->guideline('constraint-recursion-depth')
            ->text('Restricts recursion in agents to avoid runaway logic chains.')
            ->example('max-depth = 3')->key('limit')
            ->example('Monitor call stack; abort if nesting > 3')->key('validation')
            ->example('Rollback last recursive call, mark as recursion_exceeded')->key('action');

        $this->guideline('constraint-execution-time')
            ->text('Prevents long-running or hanging processes.')
            ->example('max-execution-seconds = 60')->key('limit')
            ->example('Terminate tasks exceeding runtime threshold')->key('validation')
            ->example('Abort execution and trigger recovery sequence')->key('action');

        $this->guideline('constraint-memory-usage')
            ->text('Ensures memory efficiency per agent instance.')
            ->example('max-memory = 512MB')->key('limit')
            ->example('Log and flush cache if memory usage > 512MB')->key('validation')
            ->example('Activate memory-prune in vector memory management')->key('action');

        $this->guideline('constraint-delegation-depth')
            ->text('Prevents excessive coupling across services.')
            ->example('max-dependency-depth = 5')->key('limit')
            ->example('Analyze architecture dependency graph')->key('validation');

        $this->guideline('constraint-circular-dependency')
            ->text('No module or service may depend on itself directly or indirectly.')
            ->example('forbidden')->key('limit')
            ->example('Run static dependency scan at build stage')->key('validation')
            ->example('Block merge and raise architecture-alert')->key('action');
    }
}