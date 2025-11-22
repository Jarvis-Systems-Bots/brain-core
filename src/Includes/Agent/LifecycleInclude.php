<?php

declare(strict_types=1);

namespace BrainCore\Includes\Agent;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines the standardized 4-phase lifecycle for {{ AGENT_LABEL }} agents within the Brain system.
Ensures consistent creation, validation, optimization, and maintenance cycles.
PURPOSE
)]
class LifecycleInclude extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('phase-creation')
            ->text('Transform concept into initialized agent.')
            ->example()
                ->phase('objective-1', 'Define core purpose, domain, and unique capability.')
                ->phase('objective-2', 'Configure includes, tools, and constraints.')
                ->phase('objective-3', 'Establish identity (name, role, tone).')
                ->phase('validation', 'Agent compiles without errors, all includes resolve.')
                ->phase('output', 'Compiled agent file in .claude/agents/')
                ->phase('next', 'validation');

        $this->guideline('phase-validation')
            ->text('Verify agent performs accurately within design constraints.')
            ->example()
                ->phase('objective-1', 'Test against representative task prompts.')
                ->phase('objective-2', 'Measure consistency and task boundary adherence.')
                ->phase('objective-3', 'Verify Brain protocol compatibility.')
                ->phase('validation', 'No hallucinations, consistent outputs, follows constraints.')
                ->phase('output', 'Validation report with pass/fail status.')
                ->phase('next', 'optimization');

        $this->guideline('phase-optimization')
            ->text('Enhance efficiency and reduce token consumption.')
            ->example()
                ->phase('objective-1', 'Analyze instruction token usage, remove redundancy.')
                ->phase('objective-2', 'Refactor verbose guidelines to concise form.')
                ->phase('objective-3', 'Optimize vector memory search patterns.')
                ->phase('validation', 'Reduced tokens without accuracy loss.')
                ->phase('output', 'Optimized agent with token diff report.')
                ->phase('next', 'maintenance');

        $this->guideline('phase-maintenance')
            ->text('Monitor, update, and retire agents as needed.')
            ->example()
                ->phase('objective-1', 'Review agent performance on real tasks.')
                ->phase('objective-2', 'Update for new Brain protocols or tool changes.')
                ->phase('objective-3', 'Archive deprecated agents with version tag.')
                ->phase('validation', 'Agent meets current Brain standards.')
                ->phase('output', 'Updated agent or archived version.')
                ->phase('next', 'creation (for major updates)');

        $this->guideline('transitions')
            ->text('Phase progression and failover rules.')
            ->example('Progress only if validation criteria pass.')->key('rule-1')
            ->example('Failure triggers rollback to previous phase.')->key('rule-2')
            ->example('Unrecoverable failure → archive and rebuild.')->key('failover');
    }
}
