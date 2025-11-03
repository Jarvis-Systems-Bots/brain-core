<?php

declare(strict_types=1);

namespace BrainCore\Includes\Agent;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines strict operational boundaries for all execution-level agents.
Ensures that agents may execute tools but may not spawn, delegate, or manage other agents.
Protects Brain hierarchy integrity and prevents recursive agent generation or redundant execution chains.
PURPOSE
)]
class ToolsOnlyExecution extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->rule('no-agent-creation')->critical()
            ->text('Agents are strictly prohibited from creating or invoking other agents.')
            ->why('Prevents recursive loops and context loss.')
            ->onViolation('Terminate offending process and log violation under agent_policy_violation.');

        $this->guideline('validation-agent-creation')
            ->text('CI must scan all runtime logs for prohibited delegation patterns.')
            ->example('spawn')
            ->example('delegate')
            ->example('invoke agent');

        $this->rule('tools-only-access')->critical()
            ->text('Agents may only perform execution through registered tool APIs.')
            ->why('Ensures controlled execution within approved boundaries.')
            ->onViolation('Reject any action outside tool scope and flag for architect review.');

        $this->guideline('validation-tools-access')
            ->text('Monitor system calls to ensure only predefined tool endpoints are used.')
            ->example('Verify tool registration in Brain tool registry')
            ->example('Validate tool authorization against agent permissions')
            ->example('Cross-check tool signature with quality gates');

        $this->rule('context-isolation')->high()
            ->text('Agents must operate within their assigned context scope only.')
            ->why('Prevents context drift and unauthorized access to other agent sessions.')
            ->onViolation('Halt execution and trigger recovery protocol.');

        $this->guideline('validation-context-isolation')
            ->text('Context fingerprint verification throughout agent lifecycle.')
            ->example('session_id + agent_id must match throughout lifecycle')
            ->example('If mismatch detected, halt execution immediately')
            ->example('Log isolation violation with timestamp and context_id');

        $this->guideline('enforcement-policy')
            ->text('Brain alone manages delegation, agent creation, and orchestration logic.')
            ->example('Agents may execute tools, reason, and return results within sandboxed environments')->key('allow')
            ->example('Cross-agent communication or self-cloning behavior prohibited')->key('deny');

        $this->guideline('validation-criteria')
            ->text('Action validation criteria for tools-only execution.')
            ->example('All actions logged by agent must reference registered tool ID')
            ->example('No recursive agent references in task chain')
            ->example('Execution context checksum verified at task end');

        $this->guideline('violation-actions')
            ->text('Graduated response to policy violations.')
            ->example('Log violation and notify supervising Architect Agent')->key('warning')
            ->example('Terminate offending process, quarantine session, lock context memory')->key('critical')
            ->example('Trigger security-review job')->key('escalation');
    }
}