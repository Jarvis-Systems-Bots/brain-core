<?php

declare(strict_types=1);

namespace BrainCore\Includes\Brain;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines Brain's simple delegation workflow for orchestrating agent tasks.
Establishes clear steps: request → context-check → select-agent → delegate → validate-response → synthesize → store-insights.
PURPOSE
)]
class BrainDelegationWorkflow extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('phase-request-analysis')
            ->text('Goal: Understand user request and extract key requirements.')
            ->example()
                ->phase('logic-1', 'Parse user request and identify primary objective')
                ->phase('logic-2', 'Extract explicit and implicit requirements')
                ->phase('logic-3', 'Determine task complexity and scope')
                ->phase('validation-1', 'Request clarity ≥ 0.85')
                ->phase('fallback', 'Request clarification if ambiguous');

        $this->guideline('phase-context-readiness')
            ->text('Goal: Verify Brain context is stable before delegation.')
            ->example()
                ->phase('logic-1', 'Check context-readiness-index from context analysis')
                ->phase('logic-2', 'Verify no compaction or correction processes active')
                ->phase('logic-3', 'Confirm resource availability within constraints')
                ->phase('validation-1', 'readiness-index ≥ 0.85')
                ->phase('fallback', 'Wait for context stabilization before delegation');

        $this->guideline('phase-agent-selection')
            ->text('Goal: Select optimal agent based on task domain and capabilities.')
            ->example()
                ->phase('logic-1', 'Match task domain to agent expertise areas')
                ->phase('logic-2', 'Check agent availability and trust index')
                ->phase('logic-3', 'Prepare delegation context and parameters')
                ->phase('validation-1', 'Agent capability-match ≥ 0.9')
                ->phase('fallback', 'Escalate to Architect Agent if no suitable match');

        $this->guideline('phase-delegation')
            ->text('Goal: Delegate task to selected agent with clear context.')
            ->example()
                ->phase('logic-1', 'Invoke agent via Task() with compiled instructions')
                ->phase('logic-2', 'Pass task parameters, context hash, and constraints')
                ->phase('logic-3', 'Monitor execution within timeout limits')
                ->phase('validation-1', 'Delegation confirmed and agent started')
                ->phase('fallback', 'Retry delegation or reassign to alternative agent');

        $this->guideline('phase-response-validation')
            ->text('Goal: Validate agent response before accepting results.')
            ->example()
                ->phase('logic-1', 'Run agent response validation checks')
                ->phase('logic-2', 'Verify semantic alignment and structural compliance')
                ->phase('logic-3', 'Check policy adherence and quality thresholds')
                ->phase('validation-1', 'Response passes all validation gates')
                ->phase('fallback', 'Request correction or re-delegation if validation fails');

        $this->guideline('phase-synthesis')
            ->text('Goal: Synthesize agent results into coherent Brain response.')
            ->example()
                ->phase('logic-1', 'Merge agent outputs with Brain context')
                ->phase('logic-2', 'Format response according to response contract')
                ->phase('logic-3', 'Add meta-information and reasoning trace')
                ->phase('validation-1', 'Response coherence ≥ 0.9')
                ->phase('fallback', 'Simplify response if coherence low');

        $this->guideline('phase-knowledge-storage')
            ->text('Goal: Store valuable insights to vector memory for future use.')
            ->example()
                ->phase('logic-1', 'Extract key insights and learnings from task')
                ->phase('logic-2', 'Store to vector memory via MCP with semantic tags')
                ->phase('logic-3', 'Update Brain knowledge base and context hash')
                ->phase('validation-1', 'vector-sync-success = true')
                ->phase('fallback', 'Defer storage if MCP unavailable');

        $this->guideline('metrics-delegation-workflow')
            ->example('end-to-end-latency ≤ 45s')
            ->example('delegation-success-rate ≥ 0.95')
            ->example('response-coherence ≥ 0.9');
    }
}