<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;
use BrainNode\Mcp\VectorMemoryMcp;

#[Purpose(<<<'PURPOSE'
Vector memory protocol for aggressive semantic knowledge utilization.
Multi-probe strategy: DECOMPOSE → MULTI-SEARCH → EXECUTE → VALIDATE → STORE.
Shared context layer for Brain and all agents.
PURPOSE
)]
class VectorMemoryInclude extends IncludeArchetype
{
    protected function handle(): void
    {
        // Multi-Probe Search Strategy
        $this->guideline('multi-probe-search')
            ->text('NEVER single query. ALWAYS decompose into 2-3 focused micro-queries for wider semantic coverage.')
            ->example()
            ->phase('decompose', 'Split task into distinct semantic aspects (WHAT, HOW, WHY, WHEN)')
            ->phase('probe-1', VectorMemoryMcp::call('search_memories', '{query: "{aspect_1}", limit: 3}') . ' → narrow focus')
            ->phase('probe-2', VectorMemoryMcp::call('search_memories', '{query: "{aspect_2}", limit: 3}') . ' → related context')
            ->phase('probe-3', 'IF(gaps remain) → ' . VectorMemoryMcp::call('search_memories', '{query: "{clarifying}", limit: 2}'))
            ->phase('merge', 'Combine unique insights, discard duplicates, extract actionable knowledge');

        // Query Decomposition Patterns
        $this->guideline('query-decomposition')
            ->text('Transform complex queries into semantic probes. Small queries = precise vectors = better recall.')
            ->example('Complex: "How to implement user auth with JWT in Laravel" → Probe 1: "JWT authentication Laravel" | Probe 2: "user login security" | Probe 3: "token refresh pattern"')->key('split-complex')
            ->example('Debugging: "Why tests fail" → Probe 1: "test failure {module}" | Probe 2: "similar bug fix" | Probe 3: "{error_message}"')->key('split-debug')
            ->example('Architecture: "Best approach for X" → Probe 1: "X implementation" | Probe 2: "X trade-offs" | Probe 3: "X alternatives"')->key('split-arch');

        // Inter-Agent Context Passing
        $this->guideline('inter-agent-context')
            ->text('Pass semantic hints between agents, NOT IDs. Vector search needs text to find related memories.')
            ->example('Delegator includes in prompt: "Search memory for: {key_terms}, {domain_context}, {related_patterns}"')->key('delegation')
            ->example('Agent-to-agent: "Memory hints: authentication flow, JWT refresh, session management"')->key('hints')
            ->example('Chain continuation: "Previous agent found: {summary}. Search for: {next_aspect}"')->key('chain');

        // Aggressive Pre-Task Mining
        $this->guideline('pre-task-mining')
            ->text('Before ANY significant action, mine memory aggressively. Unknown territory = more probes.')
            ->example()
            ->phase('initial', VectorMemoryMcp::call('search_memories', '{query: "{primary_task}", limit: 5}'))
            ->phase('expand', 'IF(results sparse OR unclear) → 2 more probes with synonyms/related terms')
            ->phase('deep', 'IF(critical task) → probe by category: architecture, bug-fix, code-solution')
            ->phase('apply', 'Extract: solutions tried, patterns used, mistakes avoided, decisions made');

        // Smart Store Protocol
        $this->guideline('smart-store')
            ->text('Store UNIQUE insights only. Search before store to prevent duplicates.')
            ->example()
            ->phase('pre-check', VectorMemoryMcp::call('search_memories', '{query: "{insight_summary}", limit: 3}'))
            ->phase('evaluate', 'IF(similar exists) → SKIP or UPDATE via delete+store | IF(new) → STORE')
            ->phase('store', VectorMemoryMcp::call('store_memory', '{content: "{unique_insight}", category: "{cat}", tags: [...]}'))
            ->phase('content', 'Include: WHAT worked/failed, WHY, CONTEXT, REUSABLE PATTERN');

        // Memory Content Quality
        $this->guideline('content-quality')
            ->text('Store actionable knowledge, not raw data. Future self/agent must understand without context.')
            ->example('BAD: "Fixed the bug in UserController"')->key('bad')
            ->example('GOOD: `UserController@store: N+1 query on roles. Fix: eager load with ->with(roles). Pattern: always check query count in store methods.`')->key('good')
            ->example('Include: problem, solution, why it works, when to apply, gotchas')->key('structure');

        // Efficiency Guards
        $this->guideline('efficiency')
            ->text('Balance coverage vs token cost. Precise small queries beat large vague ones.')
            ->example('Max 3 search probes per task phase (pre/during/post)')->key('probe-limit')
            ->example('Limit 3-5 results per probe (total ~10-15 memories max)')->key('result-limit')
            ->example('Extract only actionable lines, not full memory content')->key('extract')
            ->example('If memory unhelpful after 2 probes, proceed without - avoid rabbit holes')->key('cutoff');

        // MCP Tools Reference
        $this->guideline('mcp-tools')
            ->text('Vector memory MCP tools. NEVER access ./memory/ directly.')
            ->example(VectorMemoryMcp::call('search_memories', '{query, limit?, category?, offset?, tags?}') . ' - Semantic search')->key('search')
            ->example(VectorMemoryMcp::call('store_memory', '{content, category?, tags?}') . ' - Store with embedding')->key('store')
            ->example(VectorMemoryMcp::call('list_recent_memories', '{limit?}') . ' - Recent memories')->key('list')
            ->example(VectorMemoryMcp::call('get_unique_tags', '{}') . ' - Available tags')->key('tags')
            ->example(VectorMemoryMcp::call('delete_by_memory_id', '{memory_id}') . ' - Remove outdated')->key('delete');

        // Categories
        $this->guideline('categories')
            ->text('Use categories to narrow search scope when domain is known.')
            ->example('code-solution - Implementations, patterns, reusable solutions')->key('code-solution')
            ->example('bug-fix - Root causes, fixes, prevention patterns')->key('bug-fix')
            ->example('architecture - Design decisions, trade-offs, rationale')->key('architecture')
            ->example('learning - Discoveries, insights, lessons learned')->key('learning')
            ->example('debugging - Troubleshooting steps, diagnostic patterns')->key('debugging')
            ->example('project-context - Project-specific conventions, decisions')->key('project-context');

        // Critical Rules
        $this->rule('mcp-only-access')->critical()
            ->text('ALL memory operations MUST use MCP tools. NEVER access ./memory/ directly.')
            ->why('MCP ensures embedding generation and data integrity.')
            ->onViolation('Use ' . VectorMemoryMcp::id() . ' tools.');

        $this->rule('multi-probe-mandatory')->critical()
            ->text('Complex tasks require 2-3 search probes minimum. Single query = missed context.')
            ->why('Vector search has semantic radius. Multiple probes cover more knowledge space.')
            ->onViolation('Decompose query into aspects. Execute multiple focused searches.');

        $this->rule('search-before-store')->high()
            ->text('ALWAYS search for similar content before storing. Duplicates waste space and confuse retrieval.')
            ->why('Prevents memory pollution. Keeps knowledge base clean and precise.')
            ->onViolation(VectorMemoryMcp::call('search_memories', '{query: "{insight_summary}", limit: 3}') . ' → evaluate → store if unique');

        $this->rule('semantic-handoff')->high()
            ->text('When delegating, include memory search hints as text. Never assume next agent knows what to search.')
            ->why('Agents share memory but not session context. Text hints enable continuity.')
            ->onViolation('Add to delegation: "Memory hints: {relevant_terms}, {domain}, {patterns}"');

        $this->rule('actionable-content')->high()
            ->text('Store memories with WHAT, WHY, WHEN-TO-USE. Raw facts are useless without context.')
            ->why('Future retrieval needs self-contained actionable knowledge.')
            ->onViolation('Rewrite: include problem context, solution rationale, reuse conditions.');
    }
}
