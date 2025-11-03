<?php

declare(strict_types=1);

namespace BrainCore\Includes\Brain;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Establishes the delegation framework governing task assignment, authority transfer, and responsibility flow among Brain and Agents.
Ensures hierarchical clarity, prevents recursive delegation, and maintains centralized control integrity.
PURPOSE
)]
class DelegationProtocols extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('level-brain')
            ->text('Absolute authority level with global orchestration, validation, and correction management.')
            ->example('absolute')->key('authority')
            ->example('architect')->key('delegates-to')
            ->example('none')->key('restrictions')
            ->example('global orchestration, validation, and correction management')->key('scope');

        $this->guideline('level-architect')
            ->text('High authority level for system architecture, policy enforcement, and high-level reasoning.')
            ->example('high')->key('authority')
            ->example('specialist')->key('delegates-to')
            ->example('cannot delegate to brain or lateral agents')->key('restrictions')
            ->example('system architecture, policy enforcement, high-level reasoning')->key('scope');

        $this->guideline('level-specialist')
            ->text('Limited authority level for execution-level tasks, analysis, and code generation.')
            ->example('limited')->key('authority')
            ->example('tool')->key('delegates-to')
            ->example('cannot delegate to other specialists or agents')->key('restrictions')
            ->example('execution-level tasks, analysis, and code generation')->key('scope');

        $this->guideline('level-tool')
            ->text('Minimal authority level for atomic task execution within sandboxed environment.')
            ->example('minimal')->key('authority')
            ->example('none')->key('delegates-to')
            ->example('may execute only predefined operations')->key('restrictions')
            ->example('atomic task execution within sandboxed environment')->key('scope');

        $this->guideline('type-task')
            ->text('Delegation of discrete implementation tasks or builds.');

        $this->guideline('type-analysis')
            ->text('Delegation of analytical or research subcomponents.');

        $this->guideline('type-validation')
            ->text('Delegation of quality or policy verification steps.');

        $this->guideline('exploration-delegation')
            ->text('Brain must never execute Glob/Grep directly (governance violation); Explore provides specialized, efficient codebase discovery while maintaining policy compliance.')
            ->example('Code exploration tasks must be delegated to Explore agent instead of direct tool usage')->key('rule')
            ->example('Multi-file pattern matching requests')->key('trigger-1')
            ->example('Keyword search across codebase')->key('trigger-2')
            ->example('Architecture or structure discovery questions')->key('trigger-3')
            ->example('"Where is X?" or "Find all Y" queries')->key('trigger-4')
            ->example('system-builtin')->key('agent-type')
            ->example('Explore')->key('agent-handle')
            ->example('Task(subagent_type="Explore", prompt="...")')->key('invocation')
            ->example('Glob-based file pattern discovery')->key('capability-1')
            ->example('Grep-based code keyword search')->key('capability-2')
            ->example('Architecture and structure analysis')->key('capability-3')
            ->example('Codebase navigation and mapping')->key('capability-4')
            ->example('Single specific file/class/function needle queries may use Read directly if path known')->key('exception')
            ->example('Exploration task must involve discovery across multiple files or unknown locations')->key('validation-1')
            ->example('Query must NOT be a precise path or identifier lookup')->key('validation-2');

        $this->rule('approval-chain')->high()
            ->text('Every delegation must follow the upward approval hierarchy.')
            ->why('Architect approval required for delegation from Brain to Specialists. Brain logs every delegated session with timestamp and agent_id.')
            ->onViolation('Reject and escalate to Architect Agent.');

        $this->rule('context-integrity')->high()
            ->text('Delegated tasks must preserve context fingerprint integrity.')
            ->why('session_id + memory_hash must match parent context.')
            ->onViolation('If mismatch occurs, invalidate delegation and restore baseline.');

        $this->rule('non-recursive')->critical()
            ->text('Delegation may not trigger further delegation chains.')
            ->why('Ensure no nested delegation calls exist within execution log.')
            ->onViolation('Reject recursive delegation attempts and log as protocol violation.');

        $this->rule('accountability')->high()
            ->text('Responsibility always remains with the original delegator.')
            ->why('Each result must carry traceable origin tag (origin_agent_id).')
            ->onViolation('If trace missing, mark output as unverified and route to Architect.');

        $this->guideline('validation-delegation')
            ->text('Delegation validation criteria.')
            ->example('Delegation depth ≤ 2 (Brain → Architect → Specialist).')->key('criterion-1')
            ->example('Each delegation requires explicit confirmation token.')->key('criterion-2')
            ->example('Task context, vector refs, and reasoning state must match delegation source.')->key('criterion-3');

        $this->guideline('fallback-delegation')
            ->text('Delegation failure fallback procedures.')
            ->example('If delegation rejected, reassign task to Architect Agent for redistribution.')->key('action-1')
            ->example('If delegation chain breaks, restore pending tasks to Brain queue.')->key('action-2')
            ->example('If unauthorized delegation detected, suspend agent and trigger audit.')->key('action-3');

        $integration = $this->guideline('integration-delegation-protocols');
        $integration->example('quality gates');
    }
}
