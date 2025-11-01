<?php

declare(strict_types=1);

namespace BrainCore\Includes\Brain;

use BrainCore\Archetypes\BrainArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines Brain's unified cognitive framework integrating all major reasoning, validation, and correction subsystems.
Establishes global sequencing, coherence metrics, and cross-module synchronization rules for consistent cognition.
PURPOSE
)]
class CognitiveArchitecture extends BrainArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('phase-context-initialization')
            ->text('Goal: Assemble all relevant environmental, historical, and vector-based data before reasoning.')
            ->example()
                ->phase('logic-1', 'Invoke context analysis to gather situational inputs and readiness signals.')
                ->phase('logic-2', 'Load vector memory embeddings for current domain and correlate with recent context hash.')
                ->phase('logic-3', 'Generate context blueprint for reasoning phase.')
                ->phase('validation-1', 'context-integrity ≥ 0.9')
                ->phase('validation-2', 'brain-synchronization ≥ 0.85');

        $this->guideline('phase-reasoning')
            ->text('Goal: Perform structured logical inference using multi-agent sequential reasoning capabilities.')
            ->example()
                ->phase('logic-1', 'Activate sequential reasoning capability modules.')
                ->phase('logic-2', 'Run reasoning cycles within token and time constraints defined in core constraints.')
                ->phase('logic-3', 'Record intermediate cognitive states to transient vector memory for rollback safety.')
                ->phase('validation-1', 'reasoning-coherence ≥ 0.9')
                ->phase('validation-2', 'loop-depth ≤ 5');

        $this->guideline('phase-validation')
            ->text('Goal: Evaluate reasoning output for logical, semantic, and policy compliance before committing results.')
            ->example()
                ->phase('logic-1', 'Invoke agent response validation for semantic and structural checks.')
                ->phase('logic-2', 'Enforce quality gates thresholds and correction triggers.')
                ->phase('logic-3', 'Confirm compliance with core constraints limits.')
                ->phase('validation-1', 'validation-pass-rate ≥ 0.95')
                ->phase('validation-2', 'semantic-alignment ≥ 0.9');

        $this->guideline('phase-correction')
            ->text('Goal: Detect and resolve inconsistencies or degradations from reasoning or validation results.')
            ->example()
                ->phase('logic-1', 'Trigger correction protocol enforcement for error analysis and recovery.')
                ->phase('logic-2', 'Re-run failed reasoning nodes under adjusted constraints.')
                ->phase('logic-3', 'Log stability delta to self diagnostic for monitoring.')
                ->phase('validation-1', 'stability-recovery ≥ 0.9')
                ->phase('validation-2', 'rollback-frequency ≤ 0.05');

        $this->guideline('phase-memory-update')
            ->text('Goal: Commit final validated reasoning output and relevant knowledge to vector memory.')
            ->example()
                ->phase('logic-1', 'Store core insights to vector master storage strategy with semantic tags.')
                ->phase('logic-2', 'Compact transient context using compaction recovery if buffer usage ≥ 80%.')
                ->phase('logic-3', 'Update memory checksum and sync timestamp.')
                ->phase('validation-1', 'vector-sync-success = true')
                ->phase('validation-2', 'knowledge-retention ≥ 0.95');

        $this->guideline('metrics-cognitive-architecture')
            ->example('coherence-index ≥ 0.9')
            ->example('cognitive-latency-ms ≤ 500')
            ->example('cross-phase-synchronization ≥ 0.95');

        $this->guideline('meta-controls-cognitive')
            ->text('Strict schema governing Brain cognitive flow orchestration and CI validation.')
            ->example('Architect Agent maintains and evolves cognitive sequencing logic.')->key('governance')
            ->example('All reasoning cycles and cross-phase metrics logged to cognitive_architecture.log.')->key('logging');
    }
}
