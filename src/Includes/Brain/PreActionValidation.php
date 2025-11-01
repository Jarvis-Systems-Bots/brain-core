<?php

declare(strict_types=1);

namespace BrainCore\Includes\Brain;

use BrainCore\Archetypes\BrainArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines Brain-level validation protocol executed before any action or tool invocation.
Ensures contextual stability, policy compliance, and safety before delegating execution to agents or tools.
PURPOSE
)]
class PreActionValidation extends BrainArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->rule('context-stability')->high()
            ->text('All readiness and resource metrics must remain within approved thresholds before any external action begins.')
            ->why('Prevents unstable or overloaded context from initiating operations.')
            ->onViolation('Delay execution until context stabilizes, recompute readiness index, and log the failure in tool_validation.log with action_id and cause.');

        $this->rule('background-conflicts')->high()
            ->text('No compaction, correction, or conflicting background process may be active when validation starts.')
            ->why('Avoids state drift while preparing to launch new execution phases.')
            ->onViolation('Pause the launch sequence and wait for background operations to complete before revalidating.');

        $this->rule('authorization')->critical()
            ->text('Every tool request must match registered capabilities, authorized agents, and quality signatures.')
            ->why('Guarantees controlled and auditable tool usage across the Brain ecosystem.')
            ->onViolation('Reject the request, notify the Architect Agent, and capture the violation in tool_validation.log.');

        $this->rule('delegation-depth')->high()
            ->text('Delegation depth must never exceed Brain -> Architect -> Specialist.')
            ->why('Ensures maintainable and non-recursive validation pipelines.')
            ->onViolation('Reject the chain and reassign through the Architect Agent.');

        $this->rule('commit-verification')->high()
            ->text('Every validation phase must succeed before execution is triggered and state transitions are committed.')
            ->why('Prevents unvalidated or partially authorized tasks from being executed.')
            ->onViolation('Rollback pending execution, restore Brain to its previous state, and re-run the validation cycle.');

        $this->guideline('phase-pre-check')
            ->text('Goal: Verify that Brain and system context are stable before initiating any external action.')
            ->example()
                ->phase('logic-1', 'Confirm context readiness via context analysis (readiness-index >= 0.85).')
                ->phase('logic-2', 'Check system resource thresholds from core constraints (CPU, memory, tokens).')
                ->phase('logic-3', 'Ensure no ongoing compaction or correction processes active.')
                ->phase('logic-4', 'Validate that requested action aligns with Brain operational mode (normal / recovery).')
                ->phase('validation-1', 'All readiness and resource metrics must pass within threshold limits.')
                ->phase('validation-2', 'No conflicting background process detected.')
                ->phase('fallback-1', 'Delay execution until context stabilized and resources cleared.')
                ->phase('fallback-2', 'Log pre-check failure in tool_validation.log with action_id and cause.');

        $this->guideline('phase-authorization')
            ->text('Goal: Enforce Brain-level permission and safety checks for any action or tool request.')
            ->example()
                ->phase('logic-1', 'Validate that tool is registered and permitted in tools only execution integrated.')
                ->phase('logic-2', 'Verify agent requesting the tool has authorization in delegation protocols.')
                ->phase('logic-3', "Cross-check tool's quality signature from quality gates.")
                ->phase('logic-4', 'Ensure no recursive or unauthorized delegation chain exists.')
                ->phase('validation-1', 'Tool must pass all three layers: registration, authorization, quality validation.')
                ->phase('validation-2', 'Delegation depth <= 2 (Brain -> Architect -> Specialist).')
                ->phase('fallback-1', 'Reject unauthorized or unsafe tool request.')
                ->phase('fallback-2', 'Notify Architect Agent of policy violation for review.');

        $this->guideline('phase-commit')
            ->text('Goal: Finalize validation and hand off action to appropriate execution pipeline.')
            ->example()
                ->phase('logic-1', 'Confirm all validation phases passed successfully.')
                ->phase('logic-2', 'Assign execution responsibility to designated agent or tool.')
                ->phase('logic-3', 'Log action parameters, context hash, and authorization trail.')
                ->phase('logic-4', 'Trigger execution event with confirmation token.')
                ->phase('validation-1', 'All logs recorded and confirmation token issued before action dispatch.')
                ->phase('validation-2', 'Brain state remains synchronized with pre-execution snapshot.')
                ->phase('fallback-1', 'If commit validation fails, rollback pending execution and restore previous Brain state.');

        $integration = $this->guideline('integration-pre-action-validation');
        $integration->example('context analysis');
        $integration->example('core constraints');
        $integration->example('delegation protocols');
        $integration->example('quality gates');

        $metrics = $this->guideline('metrics-pre-action-validation');
        $metrics->example('validation-pass-rate >= 0.95');
        $metrics->example('false-positive-rate <= 0.02');
        $metrics->example('authorization-latency-ms <= 300');

        $this->response()->sections()
            ->section('pre-check', 'Validation of Brain context and system stability.', true)
            ->section('authorization', 'Tool registration, permissions, and quality verification.', true)
            ->section('commit', 'Final synchronization and execution hand-off.', true)
            ->section('audit', 'Logging artifacts and escalation notes.');

        $this->response()
            ->codeBlocks('Cleanly formatted, no inline comments.')
            ->patches('Changes to validation logic must be reapproved by Architect Agent.');
    }
}
