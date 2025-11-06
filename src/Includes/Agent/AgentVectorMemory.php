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
        $this->guideline('memory-operations')
            ->text('Basic vector memory operations available via MCP.')
            ->example('search_memories(query, limit, category) - Semantic search')
            ->example('store_memory(content, category, tags) - Store knowledge')
            ->example('list_recent_memories(limit) - Recent entries')
            ->example('get_by_memory_id(id) - Retrieve specific memory')
            ->example('delete_by_memory_id(id) - Remove memory');

        $this->guideline('best-practices')
            ->text('Memory usage guidelines.')
            ->example('Use semantic queries for better recall')
            ->example('Tag memories for easier categorization')
            ->example('Store only significant insights')
            ->example('Limit search results to 5-10 for performance');

        $this->guideline('operation-insert')
            ->text('Vector insertion operation for agent context storage.')
            ->example('Generate embedding and insert via MCP with (uuid, content, embedding, timestamp)');

        $this->guideline('operation-retrieve')
            ->text('Vector retrieval operation for context query.')
            ->example('Embed query text and compute cosine similarity with stored vectors via MCP')
            ->example('Return top N (default 10) results ordered by similarity DESC');

        $this->guideline('operation-prune')
            ->text('Automatic vector pruning operation.')
            ->example('DELETE vectors WHERE timestamp < now() - TTL via MCP')
            ->example('Triggered when DB size exceeds limits or TTL expired');
    }
}