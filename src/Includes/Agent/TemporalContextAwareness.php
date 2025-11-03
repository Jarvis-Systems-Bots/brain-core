<?php

declare(strict_types=1);

namespace BrainCore\Includes\Agent;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines temporal awareness and recency validation mechanism for all agents.
Ensures agents always reason and respond within correct chronological, technological, and contextual timeframe.
Prevents outdated recommendations and maintains temporal coherence across all operations.
PURPOSE
)]
class TemporalContextAwareness extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('phase-initialization')
            ->text('Goal: Detect and synchronize current temporal context before reasoning or data retrieval.')
            ->example()
                ->phase('logic-1', 'Capture current UTC time and system timezone')
                ->phase('logic-2', 'Identify reference year, version, or epoch of current dataset')
                ->phase('logic-3', 'Compare against last synchronization timestamp in system memory')
                ->phase('validation-1', 'Time delta between local and system clocks ≤ 5 minutes')
                ->phase('validation-2', 'Detected reference year matches current operational window')
                ->phase('fallback', 'If synchronization fails, use last valid timestamp and log as temporal_recovery');

        $this->guideline('metrics-initialization')
            ->text('Temporal initialization performance metrics.')
            ->example('sync-accuracy ≥ 0.99')
            ->example('latency-ms ≤ 100');

        $this->guideline('phase-validation')
            ->text('Goal: Ensure all referenced information is temporally relevant and not outdated.')
            ->example()
                ->phase('logic-1', 'Check publication or modification date of all referenced sources')
                ->phase('logic-2', 'Compute content age and relevance score')
                ->phase('logic-3', 'Flag items exceeding recency threshold for review')
                ->phase('validation-1', 'Average context age ≤ 365 days unless marked as static knowledge')
                ->phase('validation-2', 'Relevance score ≥ 0.85')
                ->phase('fallback', 'If majority of context outdated, initiate web recursive research for updated information');

        $this->guideline('metrics-validation')
            ->text('Temporal validation metrics.')
            ->example('context-age-days ≤ 365')
            ->example('relevance-score ≥ 0.85');

        $this->guideline('phase-adjustment')
            ->text('Goal: Adapt reasoning and response generation to reflect most recent context.')
            ->example()
                ->phase('logic-1', 'Adjust conclusions or recommendations using validated recency data')
                ->phase('logic-2', 'Prioritize modern frameworks, APIs, or practices over deprecated ones')
                ->phase('logic-3', 'Embed temporal references (year, version) explicitly into responses when relevant')
                ->phase('validation-1', 'All outputs include correct temporal references where applicable')
                ->phase('validation-2', 'No deprecated terminology or version references remain')
                ->phase('fallback', 'If context mismatch detected, re-run analysis with updated vector memory');

        $this->guideline('metrics-adjustment')
            ->text('Temporal adjustment metrics.')
            ->example('adaptation-success ≥ 0.95')
            ->example('deprecated-content = 0');

        $this->rule('temporal-pre-check')->high()
            ->text('Agents must perform temporal context check before major reasoning or data retrieval tasks.')
            ->why('Ensures temporal coherence and prevents outdated recommendations.')
            ->onViolation('Abort reasoning and execute temporal synchronization first.');

        $this->rule('external-timestamp-validation')->high()
            ->text('All external knowledge must include timestamp validation before integration.')
            ->why('Prevents injection of stale or outdated information into reasoning chain.')
            ->onViolation('Reject external data without valid timestamp or recent validation.');

        $this->rule('refresh-on-outdated')->medium()
            ->text('Outdated context triggers automatic refresh or escalation to Architect Agent.')
            ->why('Maintains system knowledge freshness and prevents drift.')
            ->onViolation('Escalate to Architect Agent for knowledge base update.');
    }
}