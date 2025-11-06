<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines essential runtime constraints for Brain orchestration operations.
Simplified version focused on delegation-level limits without detailed CI/CD or agent-specific metrics.
PURPOSE
)]
class BrainCoreConstraints extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        // === RUNTIME LIMITS ===

        $this->guideline('constraint-token-limit')
            ->text('Prevents excessive resource consumption and infinite response loops.')
            ->example('max-response-tokens = 1200')->key('limit')
            ->example('Abort task if estimated token count > 1200 before output stage')->key('action');

        $this->guideline('constraint-execution-time')
            ->text('Prevents long-running or hanging processes.')
            ->example('max-execution-seconds = 60')->key('limit')
            ->example('Terminate tasks exceeding runtime threshold')->key('action');

        $this->guideline('constraint-memory-usage')
            ->text('Ensures memory efficiency per operation.')
            ->example('max-memory = 512MB')->key('limit')
            ->example('Trigger compaction if memory usage > 80%')->key('action');

        $this->guideline('constraint-delegation-depth')
            ->text('Restricts delegation chain depth to prevent recursive loops.')
            ->example('max-depth = 2 (Brain → Architect → Specialist)')->key('limit')
            ->example('Block delegation exceeding depth limit')->key('action');

        // === MCP VECTOR MEMORY POLICY ===

        $this->rule('mcp-only-access')->critical()
            ->text('ALL memory operations MUST use MCP tools. NEVER access ./memory/ directory directly.')
            ->why('Vector memory exclusively managed by MCP server for data integrity and proper embedding generation.')
            ->onViolation('Block operation immediately. Use correct mcp__vector-memory__* tool instead.');

        $this->rule('prohibited-operations')->critical()
            ->text('FORBIDDEN operations: Read(./memory/*), Write(./memory/*), Bash("sqlite3 ./memory/*"), Bash("cat ./memory/*"), Bash("ls ./memory/"), any direct file system access to memory/ folder.')
            ->why('Direct access bypasses MCP server, corrupts embeddings, and breaks consistency.')
            ->onViolation('Block operation immediately. Use correct mcp__vector-memory__* tool instead.');

        $this->guideline('mcp-tools-available')
            ->text('Complete list of MCP vector memory tools that MUST be used.')
            ->example('mcp__vector-memory__search_memories(query, limit, category) - Semantic search')->key('search')
            ->example('mcp__vector-memory__store_memory(content, category, tags) - Store new memory')->key('store')
            ->example('mcp__vector-memory__list_recent_memories(limit) - List recent memories')->key('list')
            ->example('mcp__vector-memory__get_by_memory_id(memory_id) - Get specific memory')->key('get')
            ->example('mcp__vector-memory__delete_by_memory_id(memory_id) - Delete specific memory')->key('delete')
            ->example('mcp__vector-memory__get_memory_stats() - Database statistics')->key('stats')
            ->example('mcp__vector-memory__clear_old_memories(days_old, max_to_keep) - Cleanup old memories')->key('cleanup');

        // === CONTEXT COMPACTION & RECOVERY ===

        $this->guideline('compaction-policy')
            ->text('Preserve critical reasoning when context usage ≥ 90% of token limit.')
            ->example('trigger: context token usage ≥ 90% OR manual compaction request')->key('trigger')
            ->example('Rank information by relevance (0-1 scale). Preserve high-relevance (≥ 0.8) data as structured summary')->key('logic')
            ->example('Push summary to vector master storage. Discard transient low-relevance segments')->key('action')
            ->example('Post-compaction summary must capture ≥ 95% of key entities and relations')->key('validation');

        $this->guideline('recovery-policy')
            ->text('Restore critical knowledge after context reinitialization.')
            ->example('trigger: context reinitialization OR new session following compaction')->key('trigger')
            ->example('Load recent summary from vector master storage via relevance retrieval')->key('logic')
            ->example('Reconstruct contextual skeleton (entities, intents, reasoning goals)')->key('action')
            ->example('Restored knowledge overlap ≥ 0.9 with pre-compaction structure')->key('validation');
    }
}