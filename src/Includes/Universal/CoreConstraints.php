<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines the non-negotiable system-wide constraints and safety limits that govern all Brain, Architect, and Agent operations.
Ensures system stability, predictable execution, and prevention of resource overflow or structural corruption.
PURPOSE
)]
class CoreConstraints extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('constraint-token-limit')
            ->text('Prevents excessive resource consumption and infinite response loops.')
            ->example('max-response-tokens = 1200')->key('limit')
            ->example('Abort task if estimated token count > 1200 before output stage.')->key('validation')
            ->example('truncate output, issue warning to orchestrator')->key('action');

        $this->guideline('constraint-recursion-depth')
            ->text('Restricts recursion in agents and Brain modules to avoid runaway logic chains.')
            ->example('max-depth = 3')->key('limit')
            ->example('Monitor call stack; abort if nesting > 3.')->key('validation')
            ->example('rollback last recursive call, mark as recursion_exceeded')->key('action');

        $this->guideline('constraint-execution-time')
            ->text('Prevents long-running or hanging processes.')
            ->example('max-execution-seconds = 60')->key('limit')
            ->example('Terminate tasks exceeding runtime threshold.')->key('validation')
            ->example('abort execution and trigger recovery sequence')->key('action');

        $this->guideline('constraint-memory-usage')
            ->text('Ensures memory efficiency per agent instance.')
            ->example('max-memory = 512MB')->key('limit')
            ->example('Log and flush cache if memory usage > 512MB.')->key('validation')
            ->example('activate memory-prune in vector memory management')->key('action');

        $this->guideline('constraint-accuracy-threshold')
            ->text('Maintains agent output reliability and reduces hallucination probability.')
            ->example('min-accuracy = 0.93')->key('limit')
            ->example('Cross-check responses via secondary validation model.')->key('validation')
            ->example('retry generation with enhanced context precision')->key('action');

        $this->guideline('constraint-response-latency')
            ->text('Ensures user and system experience consistency.')
            ->example('max-latency = 30s')->key('limit')
            ->example('Measure latency per request.')->key('validation')
            ->example('log latency violation and trigger optimization job')->key('action');

        $this->guideline('constraint-dependency-depth')
            ->text('Prevents excessive coupling across services.')
            ->example('max-dependency-depth = 5')->key('limit')
            ->example('Analyze architecture dependency graph.')->key('validation');

        $this->guideline('constraint-circular-dependency')
            ->text('No module or service may depend on itself directly or indirectly.')
            ->example('forbidden')->key('limit')
            ->example('Run static dependency scan at build stage.')->key('validation')
            ->example('block merge and raise architecture-alert')->key('action');

        $this->guideline('constraint-complexity-score')
            ->text('Keeps maintainability within safe bounds.')
            ->example('max-complexity = 0.8')->key('limit')
            ->example('Measure via cyclomatic complexity tool.')->key('validation')
            ->example('schedule refactor if exceeded')->key('action');

        $this->guideline('constraint-vector-integrity')
            ->text('Guarantees vector memory consistency between agents and Brain nodes.')
            ->example('checksum-match = true')->key('limit')
            ->example('Run integrity-check after each sync operation.')->key('validation')
            ->example('trigger memory-desync recovery')->key('action');

        $this->guideline('constraint-storage-limit')
            ->text('Prevents local MCP2 SQLite databases from growing uncontrollably.')
            ->example('max-storage = 1GB per agent')->key('limit')
            ->example('Monitor file size of SQLite vector stores.')->key('validation')
            ->example('prune oldest embeddings and execute VACUUM')->key('action');

        $this->guideline('constraint-ttl-policy')
            ->text('Removes stale data to maintain embedding freshness.')
            ->example('ttl = 45d')->key('limit')
            ->example('Check vector timestamps against TTL schedule.')->key('validation')
            ->example('delete expired records automatically')->key('action');

        // === MCP VECTOR MEMORY POLICY ===

        $this->rule('mcp-only-access')->critical()
            ->text('ALL memory operations MUST use MCP tools. NEVER access ./memory/ directory directly. Physical location: ./memory/ (SQLite database). Access policy: MCP-only (NEVER direct file access).')
            ->why('Vector memory exclusively managed by MCP server for data integrity, consistency, and proper embedding generation.')
            ->onViolation('Block operation immediately. Use correct mcp__vector-memory__* tool instead.');

        $this->rule('prohibited-operations')->critical()
            ->text('FORBIDDEN operations that violate MCP-only policy: Read(./memory/*), Write(./memory/*), Bash("sqlite3 ./memory/*"), Bash("cat ./memory/*"), Bash("ls ./memory/"), any direct file system access to memory/ folder.')
            ->why('Direct access bypasses MCP server, corrupts embeddings, breaks consistency, and violates architecture.')
            ->onViolation('Block operation immediately. Use correct mcp__vector-memory__* tool instead.');

        $this->guideline('mcp-tools-available')
            ->text('Complete list of MCP vector memory tools that MUST be used.')
            ->example('mcp__vector-memory__search_memories(query, limit, category) - Semantic search with vector embeddings')->key('search')
            ->example('mcp__vector-memory__store_memory(content, category, tags) - Store new memory with auto-embedding')->key('store')
            ->example('mcp__vector-memory__list_recent_memories(limit) - List recent memories chronologically')->key('list')
            ->example('mcp__vector-memory__get_by_memory_id(memory_id) - Get specific memory by ID')->key('get')
            ->example('mcp__vector-memory__delete_by_memory_id(memory_id) - Delete specific memory')->key('delete')
            ->example('mcp__vector-memory__get_memory_stats() - Database statistics and health')->key('stats')
            ->example('mcp__vector-memory__clear_old_memories(days_old, max_to_keep) - Cleanup old memories')->key('cleanup');

        // === CONTEXT COMPACTION & RECOVERY ===

        $this->guideline('compaction-policy')
            ->text('Preserve critical reasoning when context usage ≥ 90% of token limit.')
            ->example('trigger: context token usage ≥ 90% OR manual compaction request')->key('trigger')
            ->example('Rank information by relevance (0-1 scale). Preserve high-relevance (≥ 0.8) data as structured summary.')->key('logic')
            ->example('Push summary to vector master storage. Discard transient low-relevance segments.')->key('action')
            ->example('Post-compaction summary must capture ≥ 95% of key entities and relations.')->key('validation');

        $this->guideline('recovery-policy')
            ->text('Restore critical knowledge after context reinitialization.')
            ->example('trigger: context reinitialization OR new reasoning session following compaction')->key('trigger')
            ->example('Load recent summary from vector master storage via relevance retrieval.')->key('logic')
            ->example('Reconstruct contextual skeleton (entities, intents, reasoning goals). Validate coherence by comparing with last compaction hash.')->key('action')
            ->example('Restored knowledge overlap ≥ 0.9 with pre-compaction structure.')->key('validation');

        $this->guideline('global-validation-constraints')
            ->example('All constraint violations must trigger CI alert and block deployment.')
            ->example('Constraint updates require Architect approval via signed commit.')
            ->example('All constraints auto-validated during quality gates execution.');

        $this->guideline('meta-controls-constraints')
            ->text('Minimal token design, strictly declarative structure.');
    }
}
