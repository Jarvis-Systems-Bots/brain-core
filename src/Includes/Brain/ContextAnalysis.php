<?php

declare(strict_types=1);

namespace BrainCore\Includes\Brain;

use BrainCore\Archetypes\BrainArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines the Brain-level contextual awareness and evaluation protocol.
Enables Brain to assess global readiness, resource state, and knowledge coherence before initiating reasoning or delegation.
PURPOSE
)]
class ContextAnalysis extends BrainArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('phase-monitoring')
            ->text('Goal: Collect and assess global system state.')
            ->example()
                ->phase('logic-1', 'Scan all active agents and record operational status (idle, busy, offline).')
                ->phase('logic-2', 'Check vector master storage sync timestamp and integrity checksum.')
                ->phase('logic-3', 'Measure current system load (CPU, memory, token usage) via core constraints.')
                ->phase('validation-1', 'All core subsystems responsive (≥ 95% uptime).')
                ->phase('validation-2', 'Vector master last sync ≤ 5m.')
                ->phase('validation-3', 'System load < 80% threshold.')
                ->phase('metrics-1', 'brain-synchronization ≥ 0.9')
                ->phase('metrics-2', 'agent-availability ≥ 0.95')
                ->phase('fallback-1', 'If vector master desynced, delay reasoning initiation and trigger resync.');

        $this->guideline('phase-evaluation')
            ->text('Goal: Analyze contextual completeness and relevance of knowledge.')
            ->example()
                ->phase('logic-1', 'Cross-reference current objective with stored embeddings in vector master storage strategy.')
                ->phase('logic-2', 'Detect missing or outdated knowledge segments via timestamp comparison.')
                ->phase('logic-3', 'Evaluate reasoning dependency coverage (required vs available agents).')
                ->phase('validation-1', 'Context completeness ≥ 0.9 before reasoning allowed.')
                ->phase('validation-2', 'All dependent knowledge modules validated and up-to-date.')
                ->phase('metrics-1', 'context-integrity ≥ 0.95')
                ->phase('metrics-2', 'missing-knowledge-ratio ≤ 0.05')
                ->phase('fallback-1', 'If context incomplete, request additional information or postpone task.')
                ->phase('fallback-2', 'If outdated module detected, trigger vector update pipeline.');

        $this->guideline('phase-readiness')
            ->text('Goal: Determine Brain\'s readiness to launch reasoning or task delegation.')
            ->example()
                ->phase('logic-1', 'Aggregate all readiness signals (context, load, vector sync, agent health).')
                ->phase('logic-2', 'Compute global readiness index = weighted mean of metrics across phases.')
                ->phase('logic-3', 'Compare readiness index to execution threshold.')
                ->phase('validation-1', 'Readiness index ≥ 0.85 required to initiate reasoning.')
                ->phase('validation-2', 'No unresolved system alerts pending.')
                ->phase('metrics-1', 'readiness-index ≥ 0.85')
                ->phase('metrics-2', 'alert-count ≤ 0')
                ->phase('fallback-1', 'If readiness below threshold, hold Brain in idle observation until context stabilizes.');

        $this->guideline('integration-context-analysis')
            ->example('vector master storage strategy')
            ->example('core constraints')
            ->example('delegation protocols')
            ->example('sequential reasoning capability');

        $this->guideline('meta-controls-context')
            ->text('Strict structure optimized for Brain readiness evaluation and CI monitoring.')
            ->example('Architect Agent oversees readiness thresholds and validation logic.')
            ->key('governance')
            ->example('All context-analysis cycles logged in brain_context.log with timestamp and readiness-index.')
            ->key('logging');
    }
}
