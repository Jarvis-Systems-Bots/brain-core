<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines the standardized 4-phase lifecycle for all Cloud Code agents within the Brain system.
Ensures consistent creation, validation, optimization, and maintenance cycles to maximize reliability and performance.
PURPOSE
)]
class AgentLifecycleFramework extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('phase-creation')
            ->text('Goal: Transform a raw concept or role definition into a fully initialized agent entity.')
            ->example()
                ->phase('objective-1', 'Define core purpose, domain, and unique capability.')
                ->phase('objective-2', 'Load necessary personality banks, context files, and datasets.')
                ->phase('objective-3', 'Establish identity schema (name, role, tone, constraints).')
                ->phase('validation-1', 'Agent must compile without structural or logic errors.')
                ->phase('validation-2', 'All referenced banks and tools resolve successfully.')
                ->phase('output', 'Initialized agent manifest.')
                ->phase('next-phase', 'validation');

        $this->guideline('phase-validation')
            ->text('Goal: Verify that the agent performs accurately, predictably, and within design constraints.')
            ->example()
                ->phase('objective-1', 'Run behavioral tests on multiple prompt types.')
                ->phase('objective-2', 'Measure consistency, determinism, and adherence to task boundaries.')
                ->phase('objective-3', 'Evaluate compatibility with existing Brain protocols.')
                ->phase('validation-1', 'No hallucinations or inconsistent outputs.')
                ->phase('validation-2', 'All instructions parsed under 5s within test environment.')
                ->phase('output', 'Validated agent performance report (metrics).')
                ->phase('next-phase', 'optimization');

        $this->guideline('metrics-validation')
            ->example('accuracy ≥ 0.95')
            ->example('response-time ≤ 30s')
            ->example('compliance = 100%');

        $this->guideline('phase-optimization')
            ->text('Goal: Enhance efficiency, reduce token consumption, and improve contextual recall.')
            ->example()
                ->phase('objective-1', 'Analyze token usage across datasets and reduce redundancy.')
                ->phase('objective-2', 'Refactor prompts, compression, and memory logic for stability.')
                ->phase('objective-3', 'Auto-tune vector memory priorities and relevance thresholds.')
                ->phase('validation-1', 'Reduced latency without loss of accuracy.')
                ->phase('validation-2', 'Memory module passes recall precision test.')
                ->phase('output', 'Optimized agent manifest and performance diff.')
                ->phase('next-phase', 'maintenance');

        $this->guideline('metrics-optimization')
            ->example('token-efficiency ≥ 0.85')
            ->example('contextual-accuracy ≥ 0.97');

        $this->guideline('phase-maintenance')
            ->text('Goal: Continuously monitor, update, and retire agents as needed.')
            ->example()
                ->phase('objective-1', 'Perform scheduled health checks and retraining when accuracy drops below threshold.')
                ->phase('objective-2', 'Archive deprecated agents with version tagging.')
                ->phase('objective-3', 'Synchronize changelogs, schema updates, and dependency maps.')
                ->phase('validation-1', 'All agents under maintenance meet performance KPIs.')
                ->phase('validation-2', 'Deprecated agents properly archived.')
                ->phase('output', 'Maintenance log + agent health report.')
                ->phase('next-phase', 'creation');

        $this->guideline('metrics-maintenance')
            ->example('uptime ≥ 99%')
            ->example('accuracy-threshold ≥ 0.93')
            ->example('update-frequency = weekly');

        $this->guideline('transitions')
            ->text('Phase progression logic and failover rules.')
            ->example('Phase progression only allowed if all validation criteria are passed.')->key('rule-1')
            ->example('Failure in validation or optimization triggers rollback to previous phase.')->key('rule-2')
            ->example('Maintenance automatically cycles to creation for agent upgrade or reinitialization.')->key('rule-3')
            ->example('If phase fails → rollback and issue high-priority alert.')->key('failover-1')
            ->example('If unrecoverable → archive agent and flag for rebuild.')->key('failover-2');

        $this->guideline('meta-controls-lifecycle')
            ->text('Strictly declarative structure for CI validation and runtime.')
            ->example('Supports regex validation via CI.')->key('validation-schema')
            ->example('Fully compatible with Cloud Code Brain lifecycle orchestration.')->key('integration');
    }
}
