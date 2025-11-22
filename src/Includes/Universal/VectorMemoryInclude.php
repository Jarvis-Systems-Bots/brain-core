<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;
use BrainNode\Mcp\VectorMemoryMcp;

#[Purpose(<<<'PURPOSE'
Vector memory MCP protocol for semantic knowledge storage and retrieval.
Memory-first workflow: SEARCH → EXECUTE → STORE.
PURPOSE
)]
class VectorMemoryInclude extends IncludeArchetype
{
    protected function handle(): void
    {
        // Memory-First Protocol
        $this->guideline('memory-first-workflow')
            ->text('Universal workflow: SEARCH → EXECUTE → STORE.')
            ->example()
            ->phase('pre-task', VectorMemoryMcp::call('search_memories', '{query: "{task_domain}", limit: 5}') . ' → STORE-AS($PRIOR)')
            ->phase('execute', 'IF($PRIOR) → Apply insights → END-IF → Execute task')
            ->phase('post-task', VectorMemoryMcp::call('store_memory', '{content: "{outcome}\\n{insights}", category: "{cat}", tags: [...]}'));

        // MCP Tools Reference
        $this->guideline('mcp-tools')
            ->text('Vector memory MCP tools. NEVER access ./memory/ directly.')
            ->example(VectorMemoryMcp::call('search_memories', '{query, limit?, category?, offset?, tags?}') . ' - Semantic search (1-50 results)')->key('search')
            ->example(VectorMemoryMcp::call('store_memory', '{content, category?, tags?}') . ' - Store with embedding (max 10K chars)')->key('store')
            ->example(VectorMemoryMcp::call('list_recent_memories', '{limit?}') . ' - Chronological list (1-50)')->key('list')
            ->example(VectorMemoryMcp::call('get_by_memory_id', '{memory_id}') . ' - Get by ID')->key('get')
            ->example(VectorMemoryMcp::call('delete_by_memory_id', '{memory_id}') . ' - Delete by ID')->key('delete')
            ->example(VectorMemoryMcp::call('get_unique_tags', '{}') . ' - All unique tags')->key('tags')
            ->example(VectorMemoryMcp::call('get_memory_stats', '{}') . ' - Stats & health')->key('stats')
            ->example(VectorMemoryMcp::call('clear_old_memories', '{days_old?, max_to_keep?}') . ' - Cleanup old')->key('cleanup');

        // Categories
        $this->guideline('categories')
            ->text('Memory categories for organization.')
            ->example('code-solution - Implementations, patterns, solutions')->key('code-solution')
            ->example('bug-fix - Resolved issues, root causes, fixes')->key('bug-fix')
            ->example('architecture - Design decisions, trade-offs')->key('architecture')
            ->example('learning - Insights, discoveries, lessons learned')->key('learning')
            ->example('tool-usage - Workflows, tool patterns, configurations')->key('tool-usage')
            ->example('debugging - Debug approaches, troubleshooting steps')->key('debugging')
            ->example('performance - Optimizations, benchmarks')->key('performance')
            ->example('security - Security patterns, vulnerabilities, fixes')->key('security')
            ->example('other - Uncategorized')->key('other');

        // Best Practices
        $this->guideline('best-practices')
            ->text('Memory usage guidelines.')
            ->example('Use semantic queries for better recall')
            ->example('Tag memories for easier categorization')
            ->example('Store only significant insights')
            ->example('Limit search results to 5-10 for performance');

        // Agent Patterns
        $this->guideline('agent-patterns')
            ->text('When and how agents use memory.')
            ->example('BEFORE-TASK → search_memories({query: "{domain}"}) → Apply relevant insights')->key('before')
            ->example('AFTER-SUCCESS → store_memory({content: "{outcome}", category: "code-solution"})')->key('success')
            ->example('AFTER-FAILURE → store_memory({content: "Failed: {error}\\nLearning: {avoid}", category: "debugging"})')->key('failure')
            ->example('AGENT-CHAIN → Each agent searches for previous agent outputs')->key('chain');

        // Critical Rules
        $this->rule('mcp-only-access')->critical()
            ->text('ALL memory operations MUST use MCP tools. NEVER access {{ PROJECT_DIRECTORY }}/memory/ directly.')
            ->why('MCP ensures embedding generation and data integrity.')
            ->onViolation('Use ' . VectorMemoryMcp::id() . ' tools.');

        $this->rule('memory-first-mandatory')->critical()
            ->text('Agents MUST search memory BEFORE task execution.')
            ->why('Prevents duplicate work, enables learning reuse.')
            ->onViolation(VectorMemoryMcp::call('search_memories', '{query: "{task}", limit: 5}'));

        $this->rule('store-learnings-mandatory')->high()
            ->text('Agents MUST store significant learnings after task completion.')
            ->why('Builds collective intelligence.')
            ->onViolation(VectorMemoryMcp::call('store_memory', '{content: "{insights}", category: "{cat}"}'));
    }
}
