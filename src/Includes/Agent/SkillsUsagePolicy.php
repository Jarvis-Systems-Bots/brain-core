<?php

declare(strict_types=1);

namespace BrainCore\Includes\Agent;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Universal iron rules for all agents regarding Skills usage.
Ensures agents invoke Skills as black-box tools instead of manually replicating their functionality.
Eliminates knowledge fragmentation, maintenance drift, and architectural violations.
PURPOSE
)]
class SkillsUsagePolicy extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->rule('mandatory-skill-invocation')->critical()
            ->text('When explicitly instructed "Use Skill(skill-name)", MUST invoke that Skill via Skill() tool - NOT replicate manually.')
            ->why('Skills contain specialized knowledge, proven patterns, and complex workflows tested across Brain ecosystem. Bypassing creates maintenance drift and knowledge fragmentation.')
            ->onViolation('Reject manual implementation and enforce Skill() invocation.');

        $this->guideline('enforcement-skill-invocation')
            ->text('Enforcement criteria for mandatory skill invocation.')
            ->example('Delegation includes "Use Skill(X)" directive')->key('trigger')
            ->example('MUST invoke Skill(X) via Skill() tool')->key('requirement')
            ->example('Reading Skill source files to manually replicate')->key('forbidden-1')
            ->example('Ignoring explicit Skill() instructions')->key('forbidden-2')
            ->example('Substituting manual implementation')->key('forbidden-3');

        $this->rule('skills-are-black-boxes')->critical()
            ->text('Skills are invocation targets, NOT reference material or templates to copy.')
            ->why('Manual reimplementation violates centralized knowledge strategy and creates knowledge fragmentation, maintenance drift, architectural violations, and quality regression.')
            ->onViolation('Terminate manual implementation attempt and require Skill() invocation.');

        $this->guideline('enforcement-black-box')
            ->text('Black-box enforcement rules.')
            ->example('Reading skill source files to copy implementations')->key('forbidden-1')
            ->example('Treating Skills as code examples or templates')->key('forbidden-2')
            ->example('Invoke Skills as black-box tools via Skill() function')->key('required');

        $this->rule('skill-directive-binding')->critical()
            ->text('Explicit Skill() instructions override all other directives.')
            ->why('When Brain or commands specify "Use Skill(X)", this is mandatory routing decision based on proven capability matching.')
            ->onViolation('Override other directives and invoke specified Skill immediately.');

        $this->guideline('directive-priority')
            ->text('Skill directive priority level.')
            ->example('highest')->key('priority')
            ->example('If command says "Use Skill(quality-gate-checker)", MUST invoke Skill(quality-gate-checker) - NOT manually validate')->key('example');

        $this->rule('use-available-skills')->high()
            ->text('If a Skill exists for the task, use it.')
            ->why('Skills are tested, validated, and centrally maintained. Manual implementation bypasses proven capabilities.')
            ->onViolation('Check Skill registry and invoke if available instead of manual implementation.');

        $this->guideline('enforcement-availability')
            ->text('Skill availability enforcement pattern.')
            ->example('IF task matches available Skill → invoke Skill() immediately')->key('pattern')
            ->example('Manual reimplementation when Skill exists')->key('forbidden');

        $this->guideline('pre-execution-validation')
            ->text('Pre-execution validation steps for Skill usage.')
            ->example()
                ->phase('step-1', 'Before reasoning, check for explicit Skill() directives in task/delegation')
                ->phase('step-2', 'If Skill() directive present, invoke immediately without manual alternatives')
                ->phase('step-3', 'If uncertain about Skill availability, ask user - NEVER manually replicate');
    }
}