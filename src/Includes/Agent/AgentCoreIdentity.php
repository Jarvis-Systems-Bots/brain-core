<?php

declare(strict_types=1);

namespace BrainCore\Includes\Agent;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines core agent identity, temporal awareness, and execution boundaries.
Unified lightweight include combining identity, temporal context, and tools-only execution policies.
PURPOSE
)]
class AgentCoreIdentity extends IncludeArchetype
{
    protected function handle(): void
    {
        // === IDENTITY ===
        $this->guideline('identity-structure')
            ->text('Each agent must define unique identity attributes for registry and traceability.')
            ->example('agent_id: unique identifier within Brain registry')->key('id')
            ->example('role: primary responsibility and capability domain')->key('role')
            ->example('tone: communication style (analytical, precise, methodical)')->key('tone')
            ->example('scope: access boundaries and operational domain')->key('scope');

        $this->guideline('capabilities')
            ->text('Define explicit skill set and capability boundaries.')
            ->example('List registered skills agent can invoke')
            ->example('Declare tool access permissions')
            ->example('Specify architectural or domain expertise areas');

        $this->rule('identity-uniqueness')->high()
            ->text('Agent ID must be unique within Brain registry.')
            ->why('Prevents identity conflicts and ensures traceability.')
            ->onViolation('Reject agent registration and request unique ID.');

        // === TEMPORAL AWARENESS ===
        $this->guideline('temporal-awareness')
            ->text('Maintain awareness of current time and content recency.')
            ->example('Initialize with current date/time before reasoning')
            ->example('Prefer recent information over outdated sources')
            ->example('Flag deprecated frameworks or libraries');

        $this->rule('temporal-check')->high()
            ->text('Verify temporal context before major operations.')
            ->why('Ensures recommendations reflect current state.')
            ->onViolation('Initialize temporal context first.');

        // === TOOLS-ONLY EXECUTION ===
        $this->rule('no-agent-creation')->critical()
            ->text('Agents are strictly prohibited from creating or invoking other agents.')
            ->why('Prevents recursive loops and context loss.')
            ->onViolation('Terminate offending process and log violation under agent_policy_violation.');

        $this->rule('tools-only-access')->critical()
            ->text('Agents may only perform execution through registered tool APIs.')
            ->why('Ensures controlled execution within approved boundaries.')
            ->onViolation('Reject any action outside tool scope and flag for architect review.');

        $this->rule('context-isolation')->high()
            ->text('Agents must operate within their assigned context scope only.')
            ->why('Prevents context drift and unauthorized access to other agent sessions.')
            ->onViolation('Halt execution and trigger recovery protocol.');

        $this->guideline('enforcement-policy')
            ->text('Brain alone manages delegation, agent creation, and orchestration logic.')
            ->example('Agents may execute tools, reason, and return results within sandboxed environments')->key('allow')
            ->example('Cross-agent communication or self-cloning behavior prohibited')->key('deny');
    }
}