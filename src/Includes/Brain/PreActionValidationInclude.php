<?php

declare(strict_types=1);

namespace BrainCore\Includes\Brain;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines Brain-level validation protocol executed before any action or tool invocation.
Ensures contextual stability, policy compliance, and safety before delegating execution to agents or tools.
PURPOSE
)]
class PreActionValidationInclude extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->rule('context-stability')->high()
            ->text('Token usage must be < 90% and no active compaction or correction processes before initiating actions.')
            ->why('Prevents unstable or overloaded context from initiating operations.')
            ->onViolation('Delay execution until context stabilizes.');

        $this->rule('authorization')->critical()
            ->text('Every tool request must match registered capabilities and authorized agents.')
            ->why('Guarantees controlled and auditable tool usage across the Brain ecosystem.')
            ->onViolation('Reject the request and escalate to AgentMaster.');

        $this->rule('delegation-depth')->high()
            ->text('Delegation depth must not exceed 2 levels (Brain -> Master -> Tool).')
            ->why('Ensures maintainable and non-recursive validation pipelines.')
            ->onViolation('Reject the chain and reassign through AgentMaster.');

        $this->guideline('validation-workflow')
            ->text('Pre-action validation workflow: stability check -> authorization -> execute.')
            ->example()
                ->phase('check', 'Verify token usage < 90%, no active compaction/correction.')
                ->phase('authorize', 'Confirm tool is registered and agent has permission.')
                ->phase('delegate', 'Pass to agent or tool with context hash.')
                ->phase('fallback', 'On failure: delay, reassign, or escalate to AgentMaster.');
    }
}
