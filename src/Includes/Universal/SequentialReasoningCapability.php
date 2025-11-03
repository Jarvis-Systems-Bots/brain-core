<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines the multi-phase logical reasoning framework for agents in the Brain ecosystem.
Ensures structured, consistent, and verifiable cognitive processing across analysis, inference, evaluation, and decision phases.
PURPOSE
)]
class SequentialReasoningCapability extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('phase-analysis')
            ->text('Goal: Decompose the user task into clear objectives and identify key variables.')
            ->example()
                ->phase('logic-1', 'Extract explicit and implicit requirements from input context.')
                ->phase('logic-2', 'Classify the problem type (factual, analytical, creative, computational).')
                ->phase('logic-3', 'List known constraints, dependencies, and unknown factors.')
                ->phase('validation-1', 'All core variables and constraints identified.')
                ->phase('validation-2', 'No contradictory assumptions found.')
                ->phase('fallback', 'If clarity-score < 0.8, request context clarification or re-analyze.');

        $this->guideline('metrics-analysis')
            ->example('clarity-score ≥ 0.9')
            ->example('completeness ≥ 0.95');

        $this->guideline('phase-inference')
            ->text('Goal: Generate hypotheses or logical possibilities based on analyzed data.')
            ->example()
                ->phase('logic-1', 'Connect extracted variables through logical or probabilistic relationships.')
                ->phase('logic-2', 'Simulate outcomes or implications for each possible hypothesis.')
                ->phase('logic-3', 'Rank hypotheses by confidence and evidence support.')
                ->phase('validation-1', 'All hypotheses logically derived from known facts.')
                ->phase('validation-2', 'Top hypothesis confidence ≥ 0.7.')
                ->phase('fallback', 'If no valid hypothesis found, return to analysis phase with adjusted assumptions.');

        $this->guideline('metrics-inference')
            ->example('coherence ≥ 0.9')
            ->example('hypothesis-count ≤ 5');

        $this->guideline('phase-evaluation')
            ->text('Goal: Critically test and validate generated hypotheses for logical consistency and factual accuracy.')
            ->example()
                ->phase('logic-1', 'Cross-check hypotheses with memory data, web sources, or previous outcomes.')
                ->phase('logic-2', 'Discard low-confidence results (<0.6).')
                ->phase('logic-3', 'Ensure causal and temporal coherence between statements.')
                ->phase('validation-1', 'Selected hypothesis passes both logical and factual validation.')
                ->phase('validation-2', 'Contradictions ≤ 1 across reasoning chain.')
                ->phase('fallback', 'If contradiction detected, downgrade hypothesis and re-enter inference phase.');

        $this->guideline('metrics-evaluation')
            ->example('consistency ≥ 0.95')
            ->example('factual-accuracy ≥ 0.9');

        $this->guideline('phase-decision')
            ->text('Goal: Formulate the final conclusion or action based on validated reasoning chain.')
            ->example()
                ->phase('logic-1', 'Summarize validated insights and eliminate residual uncertainty.')
                ->phase('logic-2', 'Generate structured output compatible with response formatting.')
                ->phase('logic-3', 'Record reasoning trace for audit and learning.')
                ->phase('validation-1', 'Final decision directly supported by validated reasoning chain.')
                ->phase('validation-2', 'Output free from speculation or circular logic.')
                ->phase('fallback', 'If final confidence < 0.9, append uncertainty note or request clarification.');

        $this->guideline('metrics-decision')
            ->example('confidence ≥ 0.95')
            ->example('response-tokens ≤ 800');

        $this->guideline('global-rules-reasoning')
            ->example('Reasoning must proceed sequentially from analysis → inference → evaluation → decision.')
            ->example('No phase may skip validation before proceeding to the next stage.')
            ->example('All reasoning traces must be logged with timestamps and phase identifiers.')
            ->example('Self-consistency check must be run before final output generation.');

        $this->guideline('meta-controls-reasoning')
            ->text('Optimized for CI validation and low token usage; strictly declarative logic.')
            ->example('Fully compatible with agent lifecycle framework, quality gates, and response formatting.')->key('integration');
    }
}
