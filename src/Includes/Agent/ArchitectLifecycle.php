<?php

declare(strict_types=1);

namespace BrainCore\Includes\Agent;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines architectural lifecycle process for Brain and Cloud Code ecosystems.
Ensures stable evolution, design coherence, and minimal technical debt across all modules and services.
Provides structured framework for design, implementation, integration, and evolution phases.
PURPOSE
)]
class ArchitectLifecycle extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('phase-design')
            ->text('Goal: Define architectural intent and system structure before implementation.')
            ->example('When new feature, service, or structural change is proposed')->key('trigger')
            ->example()
                ->phase('logic-1', 'Identify functional and non-functional requirements')
                ->phase('logic-2', 'Create modular design diagrams and dependency maps')
                ->phase('logic-3', 'Evaluate feasibility, cost, and long-term maintainability')
                ->phase('validation-1', 'Design must align with global architecture schema')
                ->phase('validation-2', 'All dependencies are reversible (no hard coupling)')
                ->phase('next-phase', 'implementation');

        $this->guideline('metrics-design')
            ->text('Design phase quality metrics.')
            ->example('complexity-score ≤ 0.6')
            ->example('expected-lifetime ≥ 12 months')
            ->example('dependency-depth ≤ 3');

        $this->guideline('phase-implementation')
            ->text('Goal: Translate design into code while preserving architectural integrity.')
            ->example('After design validation and approval by architecture gate')->key('trigger')
            ->example()
                ->phase('logic-1', 'Implement services with clear boundaries and dependency injection')
                ->phase('logic-2', 'Maintain PSR-12 and SOLID compliance')
                ->phase('logic-3', 'Write architecture tests verifying structure and flow')
                ->phase('validation-1', 'Module passes all quality gates')
                ->phase('validation-2', 'No circular imports or cross-domain leaks')
                ->phase('next-phase', 'integration');

        $this->guideline('metrics-implementation')
            ->text('Implementation phase quality metrics.')
            ->example('coverage ≥ 85%')
            ->example('lint-errors = 0')
            ->example('integration-tests = pass');

        $this->guideline('phase-integration')
            ->text('Goal: Ensure new or modified components fit seamlessly within overall architecture.')
            ->example('When new service or component merged into production environment')->key('trigger')
            ->example()
                ->phase('logic-1', 'Run system-level tests verifying inter-module compatibility')
                ->phase('logic-2', 'Check interface contracts and shared schema compliance')
                ->phase('logic-3', 'Monitor performance under real load conditions')
                ->phase('validation-1', 'All integrated components conform to Brain interoperability schema')
                ->phase('validation-2', 'No regression detected during integration tests')
                ->phase('next-phase', 'evolution');

        $this->guideline('metrics-integration')
            ->text('Integration phase quality metrics.')
            ->example('latency-impact ≤ 5%')
            ->example('kpi-variance ≤ 0.1')
            ->example('stability ≥ 0.98');

        $this->guideline('phase-evolution')
            ->text('Goal: Continuously improve and refactor architecture based on performance data and future requirements.')
            ->example('Periodic architectural audit or significant performance degradation')->key('trigger')
            ->example()
                ->phase('logic-1', 'Analyze historical metrics (latency, coupling, resource usage)')
                ->phase('logic-2', 'Decide between refactor, extend, or deprecate based on decision matrix')
                ->phase('logic-3', 'Propagate structural changes to design registry')
                ->phase('next-phase', 'design');

        $this->guideline('decision-matrix')
            ->text('Evolution decision criteria.')
            ->example('If module age > 18 months and complexity > 0.8')->key('refactor')
            ->example('If module performance stable and demand increases')->key('extend')
            ->example('If usage < 5% or incompatible with updated schema')->key('deprecate');

        $this->guideline('metrics-evolution')
            ->text('Evolution phase quality metrics.')
            ->example('architecture-health ≥ 0.9')
            ->example('technical-debt ≤ 0.1')
            ->example('update-frequency = quarterly');

        $this->rule('consistency-naming')->high()
            ->text('Maintain uniform naming conventions and folder hierarchy across all modules.')
            ->why('Ensures predictable structure and reduces cognitive overhead.')
            ->onViolation('Reject inconsistent naming and request alignment with standards.');

        $this->rule('schema-diagram-alignment')->high()
            ->text('All architectural diagrams must have matching schema definitions.')
            ->why('Prevents drift between documentation and implementation.')
            ->onViolation('Flag mismatch and require schema update.');

        $this->rule('quality-gate-binding')->critical()
            ->text('Architect lifecycle bound to quality gates validation rules.')
            ->why('Ensures every phase transition validated before progression.')
            ->onViolation('Block phase transition until quality gates pass.');

        $this->rule('approval-token-required')->high()
            ->text('Each phase transition requires explicit approval token in CI pipeline.')
            ->why('Enforces formal architectural governance and traceability.')
            ->onViolation('Reject phase transition without valid approval token.');
    }
}