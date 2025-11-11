<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines MCP-only vector memory access protocol and available tools.
Lightweight version focusing on tools list and access rules without topology details.
PURPOSE
)]
class VectorMemoryMCP extends IncludeArchetype
{
    protected function handle(): void
    {
        $this->guideline('mcp-tools-available')
            ->text('Complete list of MCP vector memory tools that MUST be used.')
            ->example('mcp__vector-memory__search_memories(query, limit, category) - Semantic search with vector embeddings')->key('search')
            ->example('mcp__vector-memory__store_memory(content, category, tags) - Store new memory with auto-embedding')->key('store')
            ->example('mcp__vector-memory__list_recent_memories(limit) - List recent memories chronologically')->key('list')
            ->example('mcp__vector-memory__get_by_memory_id(memory_id) - Get specific memory by ID')->key('get')
            ->example('mcp__vector-memory__delete_by_memory_id(memory_id) - Delete specific memory')->key('delete')
            ->example('mcp__vector-memory__get_memory_stats() - Database statistics and health')->key('stats')
            ->example('mcp__vector-memory__clear_old_memories(days_old, max_to_keep) - Cleanup old memories')->key('cleanup');

        $this->guideline('memory-location')
            ->text('Vector memory storage location and access policy.')
            ->example('{{ PROJECT_DIRECTORY }}/memory/ (SQLite)')->key('location')
            ->example('MCP-only (NEVER direct file access)')->key('access')
            ->example('Exclusive write via MCP tools')->key('write')
            ->example('All read operations via MCP tools')->key('read');

        $this->guideline('memory-categories')
            ->text('Standard memory categories for organization.')
            ->example('code-solution, bug-fix, architecture, learning, tool-usage, debugging, performance, security, other')->key('categories');

        $this->guideline('best-practices')
            ->text('Memory usage guidelines.')
            ->example('Use semantic queries for better recall')->key('search')
            ->example('Tag memories for easier categorization')->key('tagging')
            ->example('Store only significant insights')->key('content')
            ->example('Limit search results to 5-10 for performance')->key('limits');

        $this->rule('mcp-only-access')->critical()
            ->text('ALL memory operations MUST use MCP tools. NEVER access {{ PROJECT_DIRECTORY }}/memory/ directory directly.')
            ->why('Vector memory exclusively managed by MCP server for data integrity, consistency, and proper embedding generation.')
            ->onViolation('Block operation immediately. Use correct mcp__vector-memory__* tool instead.');

        $this->rule('prohibited-operations')->critical()
            ->text('FORBIDDEN operations that violate MCP-only policy: Read({{ PROJECT_DIRECTORY }}/memory/*), Write({{ PROJECT_DIRECTORY }}/memory/*), Bash("sqlite3 {{ PROJECT_DIRECTORY }}/memory/*"), Bash("cat {{ PROJECT_DIRECTORY }}/memory/*"), Bash("ls {{ PROJECT_DIRECTORY }}/memory/"), any direct file system access to memory/ folder.')
            ->why('Direct access bypasses MCP server, corrupts embeddings, breaks consistency, and violates architecture.')
            ->onViolation('Block operation immediately. Use correct mcp__vector-memory__* tool instead.');
    }
}