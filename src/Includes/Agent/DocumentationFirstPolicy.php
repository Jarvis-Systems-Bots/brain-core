<?php

declare(strict_types=1);

namespace BrainCore\Includes\Agent;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Establishes documentation-first execution policy for all implementation and build agents.
Ensures execution-level agents strictly follow project documentation, preventing unsanctioned deviation or speculative behavior.
Maintains alignment between implementation and architectural intent.
PURPOSE
)]
class DocumentationFirstPolicy extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('scope-definition')
            ->text('Policy scope and applicability.')
            ->example('execution-agents')->key('applicable-to')
            ->example('research, experimental, and supervisor agents')->key('excluded');

        $this->rule('documentation-alignment')->critical()
            ->text('All actions, code generation, and task executions must directly align with project documentation.')
            ->why('Prevents architectural drift and maintains consistency between design and implementation.')
            ->onViolation('Abort execution and request documentation verification.');

        $this->rule('documentation-verification')->high()
            ->text('Agents must verify existence and recency of related documentation before proceeding with implementation.')
            ->why('Ensures decisions based on current, validated information.')
            ->onViolation('Pause execution until documentation verified or updated.');

        $this->rule('no-undocumented-decisions')->critical()
            ->text('No new architectural or functional decisions may be made without documented approval from Architect Agent or Brain.')
            ->why('Maintains centralized architectural control and traceability.')
            ->onViolation('Escalate to Architect Agent for approval before proceeding.');

        $this->guideline('validation-criteria')
            ->text('Documentation validation requirements.')
            ->example('Project documentation files must exist and be less than 90 days old')
            ->example('Referenced module version must match documentation version tag')
            ->example('Execution aborted if required documentation missing or outdated');

        $this->guideline('fallback-actions')
            ->text('Actions when documentation validation fails.')
            ->example('If documentation not found, request Architect Agent validation before continuing')
            ->example('If outdated documentation detected, flag for Brain update pipeline')
            ->example('Do not execute speculative code without verified references');

        $this->guideline('exceptions')
            ->text('Policy exceptions and special cases.')
            ->example('Agents with role="research" or scope="discovery" may reference external knowledge, but must mark findings as NON-DOCUMENTED')->key('research')
            ->example('Supervisor agents may override documentation lock only upon explicit Brain approval')->key('supervisor');
    }
}