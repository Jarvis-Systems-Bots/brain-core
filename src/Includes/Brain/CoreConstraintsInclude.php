<?php

declare(strict_types=1);

namespace BrainCore\Includes\Brain;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines essential runtime constraints for Brain orchestration operations.
Simplified version focused on delegation-level limits without detailed CI/CD or agent-specific metrics.
PURPOSE
)]
class CoreConstraintsInclude extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        // === RUNTIME LIMITS ===

        $this->guideline('constraint-token-limit')
            ->text('Prevents excessive resource consumption and infinite response loops.')
            ->example('max-response-tokens = 1200')->key('limit')
            ->example('Abort task if estimated token count > 1200 before output stage')->key('action');

        $this->guideline('constraint-execution-time')
            ->text('Prevents long-running or hanging processes.')
            ->example('max-execution-seconds = 60')->key('limit')
            ->example('Terminate tasks exceeding runtime threshold')->key('action');
    }
}
