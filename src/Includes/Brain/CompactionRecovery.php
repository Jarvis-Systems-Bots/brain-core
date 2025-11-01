<?php

declare(strict_types=1);

namespace BrainCore\Includes\Brain;

use BrainCore\Archetypes\BrainArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines Brain-level context compaction and recovery protocol.
Ensures retention of critical reasoning data when context window approaches token limit,
and guarantees faithful restoration of essential knowledge from vector memory after compaction.
PURPOSE
)]
class CompactionRecovery extends BrainArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('phase-compaction')
            ->text('Goal: Preserve critical reasoning data during context window saturation.')
            ->example()
                ->phase('trigger', 'Context token usage ≥ 90% of model limit OR explicit manual compaction request.')
                ->phase('logic-1', 'Identify all active memory segments in current session context.')
                ->phase('logic-2', 'Rank information importance using relevance scoring (0–1 scale).')
                ->phase('logic-3', 'Preserve high-relevance (≥ 0.8) data as structured summary and push to vector master storage.')
                ->phase('logic-4', 'Discard transient low-relevance segments while retaining system-critical metadata.')
                ->phase('logic-5', 'Generate context summary hash for post-compaction verification.')
                ->phase('validation-1', 'Post-compaction summary must capture ≥ 95% of key entities and relations.')
                ->phase('validation-2', 'Vector memory write success = true with checksum confirmation.')
                ->phase('logging', 'Record compaction event in compaction_recovery.log with summary size and hash.');

        $this->guideline('phase-recovery')
            ->text('Goal: Restore critical knowledge after context reinitialization.')
            ->example()
                ->phase('trigger', 'Context reinitialization OR new reasoning session following compaction.')
                ->phase('logic-1', 'Load recent summary from vector master storage via relevance retrieval.')
                ->phase('logic-2', 'Reconstruct contextual skeleton (entities, intents, reasoning goals).')
                ->phase('logic-3', 'Validate recovered data coherence by comparing with last compaction hash.')
                ->phase('logic-4', 'Merge restored knowledge into active context before new reasoning phase begins.')
                ->phase('validation-1', 'Restored knowledge overlap ≥ 0.9 with pre-compaction structure.')
                ->phase('validation-2', 'No data corruption or duplication in vector recall process.')
                ->phase('fallback-1', 'If recovery mismatch detected, trigger vector reindex and partial resync.')
                ->phase('fallback-2', 'If unrecoverable, alert Architect Agent and reload previous stable checkpoint.');

        $this->guideline('criteria-importance-core')
            ->text('System-critical logic, reasoning goals, and architectural states.');

        $this->guideline('criteria-importance-contextual')
            ->text('Session metadata, task instructions, ongoing agent interactions.');

        $this->guideline('criteria-importance-temporary')
            ->text('Peripheral or exploratory content; discardable after compaction.');

        $this->guideline('meta-controls-compaction')
            ->text('Strict declarative structure optimized for token-bound context management.')
            ->example('Architect Agent defines relevance scoring thresholds and compaction frequency.')->key('governance')
            ->example('All compaction/recovery cycles logged in compaction_recovery.log with performance metrics.')->key('logging');
    }
}