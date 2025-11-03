<?php

declare(strict_types=1);

namespace BrainCore\Includes\Agent;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines operational rules, policies, and maintenance routines for agent vector memory via MCP.
Ensures efficient context storage, retrieval, pruning, and synchronization for agent-level operations.
Complements master storage strategy with agent-specific memory management patterns.
PURPOSE
)]
class AgentVectorMemory extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('operation-insert')
            ->text('Vector insertion operation for agent context storage.')
            ->example('On new message, task creation, or context addition')->key('trigger')
            ->example()
                ->phase('logic-1', 'Generate embedding vector using local model or external encoder')
                ->phase('logic-2', 'Insert record via MCP with fields (uuid, content, embedding, timestamp)')
                ->phase('logic-3', 'Update index on embedding dimension if required');

        $this->guideline('policy-insert')
            ->text('Agent-level insertion policies and limits.')
            ->example('max-vectors = 100000')->key('limit')
            ->example('ttl = 45d')->key('ttl')
            ->example('On overflow → trigger prune oldest vectors by timestamp ASC')->key('overflow');

        $this->guideline('metrics-insert')
            ->text('Insertion performance metrics.')
            ->example('avg-embedding-size = 1536')
            ->example('insert-latency-ms ≤ 5');

        $this->guideline('operation-retrieve')
            ->text('Vector retrieval operation for context query.')
            ->example('On context query or recall event')->key('trigger')
            ->example()
                ->phase('logic-1', 'Embed query text and compute cosine similarity with stored vectors via MCP')
                ->phase('logic-2', 'Return top N (default 10) results ordered by similarity DESC');

        $this->guideline('policy-retrieve')
            ->text('Retrieval policies and thresholds.')
            ->example('recall-threshold ≥ 0.78')
            ->example('max-results = 25');

        $this->guideline('fallback-retrieve')
            ->text('Retrieval fallback actions.')
            ->example('If no results found, expand search threshold by -0.05')
            ->example('If still empty, query backup vector store via MCP');

        $this->guideline('metrics-retrieve')
            ->text('Retrieval performance metrics.')
            ->example('recall-accuracy ≥ 0.95')
            ->example('retrieval-latency-ms ≤ 15');

        $this->guideline('operation-prune')
            ->text('Automatic vector pruning operation.')
            ->example('Triggered when DB size exceeds 512MB or TTL expired vectors > 1%')->key('trigger')
            ->example()
                ->phase('logic-1', 'DELETE vectors WHERE timestamp < now() - TTL via MCP')
                ->phase('logic-2', 'Rebuild index if needed');

        $this->guideline('policy-prune')
            ->text('Pruning policies and maintenance.')
            ->example('max-disk-usage = 512MB')
            ->example('vacuum-after = true');

        $this->guideline('metrics-prune')
            ->text('Pruning metrics.')
            ->example('pruned-records = dynamic')
            ->example('cleanup-time-ms ≤ 50');

        $this->guideline('operation-sync')
            ->text('Vector synchronization with master storage.')
            ->example('On scheduled backup or Brain synchronization request')->key('trigger')
            ->example()
                ->phase('logic-1', 'Compare local vector checksum with master via MCP')
                ->phase('logic-2', 'If mismatch detected, push delta to master or pull corrected entries');

        $this->guideline('policy-sync')
            ->text('Synchronization policies.')
            ->example('mode = bidirectional')->key('mode')
            ->example('conflict-resolution = prefer-latest-timestamp')->key('resolution')
            ->example('schedule = every 6h')->key('schedule');

        $this->guideline('metrics-sync')
            ->text('Synchronization metrics.')
            ->example('sync-success ≥ 99%')
            ->example('checksum-match = true');

        $this->guideline('operation-integrity')
            ->text('Vector storage integrity check.')
            ->example('Executed daily or after crash recovery')->key('trigger')
            ->example()
                ->phase('logic-1', 'Run integrity check via MCP; verify no corruption')
                ->phase('logic-2', 'Check vector dimensions uniformity across records');

        $this->guideline('policy-integrity')
            ->text('Integrity check policies.')
            ->example('rebuild-on-fail = true')
            ->example('backup-before-rebuild = true');

        $this->guideline('metrics-integrity')
            ->text('Integrity check metrics.')
            ->example('corruption-rate = 0')
            ->example('check-duration-ms ≤ 20');

        $this->guideline('cache-policy')
            ->text('Agent-level caching strategy.')
            ->example('strategy = LRU')->key('type')
            ->example('max-cache-size = 256MB')->key('limit')
            ->example('hot-context-priority ≥ 0.85 similarity score')->key('priority')
            ->example('flush-interval = 30m')->key('interval');

        $this->rule('mcp-only-access')->critical()
            ->text('ALL agent memory operations MUST go through MCP tools - NEVER direct file access.')
            ->why('Ensures data integrity, synchronization, and proper access control.')
            ->onViolation('Reject direct file access and enforce MCP tool usage.');
    }
}