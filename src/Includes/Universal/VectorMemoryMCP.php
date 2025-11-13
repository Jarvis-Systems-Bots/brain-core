<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;
use BrainNode\Mcp\VectorMemoryMcp as VectorMemoryMcpTool;

#[Purpose(<<<'PURPOSE'
Vector memory is the PRIMARY knowledge base for ALL agents and subagents.
Establishes memory-first workflow: search before execution, store after completion.
Compact protocol using pseudo-syntax for maximum efficiency.
PURPOSE
)]
class VectorMemoryMCP extends IncludeArchetype
{
    protected function handle(): void
    {
        // Memory-First Protocol
        $this->guideline('memory-first-workflow')
            ->text('Universal workflow: SEARCH → EXECUTE → STORE. All agents MUST check memory before execution and store learnings after.')
            ->example()
            ->phase('pre-task', VectorMemoryMcpTool::call('search_memories', '{query: "{task_domain}", limit: 5, category: "code-solution,learning"}') . ' → STORE-AS($PRIOR_KNOWLEDGE)')
            ->phase('task-context', 'IF($PRIOR_KNOWLEDGE not empty) → THEN → [Apply insights from $PRIOR_KNOWLEDGE] → END-IF')
            ->phase('execution', 'Execute task with context from $PRIOR_KNOWLEDGE')
            ->phase('post-task', VectorMemoryMcpTool::call('store_memory', '{content: "Task: {outcome}\\n\\nApproach: {method}\\n\\nLearnings: {insights}", category: "{category}", tags: ["{tag1}", "{tag2}"]}'));

        // Core Tools
        $this->guideline('mcp-tools')
            ->text('MCP vector memory tools (MCP-only, NEVER direct file access).')
            ->example(VectorMemoryMcpTool::call('search_memories', '{query, limit, category}') . ' - Semantic search')->key('search')
            ->example(VectorMemoryMcpTool::call('store_memory', '{content, category, tags}') . ' - Store with embedding')->key('store')
            ->example(VectorMemoryMcpTool::call('list_recent_memories', '{limit}') . ' - Recent chronological')->key('list')
            ->example(VectorMemoryMcpTool::call('get_by_memory_id', '{memory_id}') . ' - Get by ID')->key('get')
            ->example(VectorMemoryMcpTool::call('delete_by_memory_id', '{memory_id}') . ' - Delete by ID')->key('delete')
            ->example(VectorMemoryMcpTool::call('get_memory_stats', '{}') . ' - Stats & health')->key('stats')
            ->example(VectorMemoryMcpTool::call('clear_old_memories', '{days_old, max_to_keep}') . ' - Cleanup')->key('cleanup');

        // Categories & Best Practices
        $this->guideline('memory-usage')
            ->text('Categories: code-solution, bug-fix, architecture, learning, tool-usage, debugging, performance, security, other. Store significant insights only, use semantic queries, limit results to 5-10.')
            ->example('Categories: code-solution (implementations), bug-fix (resolved issues), architecture (design decisions), learning (insights), tool-usage (workflows)')->key('categories')
            ->example('Semantic queries: "Laravel authentication patterns" better than "auth code"')->key('search-quality')
            ->example('Tags: ["feature-name", "component", "pattern-type"] for better organization')->key('tagging')
            ->example('Limit: 5-10 results optimal (balance: context vs noise)')->key('limits');

        // Agent Integration Patterns
        $this->guideline('agent-patterns')
            ->text('Common agent memory patterns using pseudo-syntax.')
            ->example()
            ->phase('pattern-1', 'BEFORE-TASK → ' . VectorMemoryMcpTool::call('search_memories', '{query: "{domain}", limit: 5}') . ' → Review & apply')
            ->phase('pattern-2', 'AFTER-SUCCESS → ' . VectorMemoryMcpTool::call('store_memory', '{content: "{outcome}\\n\\n{insights}", category: "code-solution", tags: [...]}'))
            ->phase('pattern-3', 'AFTER-FAILURE → ' . VectorMemoryMcpTool::call('store_memory', '{content: "Failed: {error}\\n\\nLearning: {what-to-avoid}", category: "debugging", tags: [...]}'))
            ->phase('pattern-4', 'KNOWLEDGE-REUSE → ' . VectorMemoryMcpTool::call('search_memories', '{query: "similar to {current_task}", limit: 5}') . ' → Adapt solution');

        // When to Use Memory
        $this->guideline('memory-triggers')
            ->text('Situations requiring memory interaction.')
            ->example('Starting new task → Search for similar past solutions')->key('task-start')
            ->example('Encountering complex problem → Search for patterns/approaches')->key('problem-solving')
            ->example('After implementing solution → Store approach & learnings')->key('task-complete')
            ->example('After bug fix → Store root cause & fix method')->key('bug-resolution')
            ->example('Making architectural decision → Store rationale & trade-offs')->key('architecture')
            ->example('Discovering pattern/insight → Store for future reference')->key('discovery')
            ->example('Between sequential agent steps → Next agent searches previous results')->key('agent-continuity');

        // Critical Rules
        $this->rule('mcp-only-access')->critical()
            ->text('ALL memory operations MUST use MCP tools. NEVER access {{ PROJECT_DIRECTORY }}/memory/ directly via Read/Write/Bash.')
            ->why('MCP server ensures embedding generation, data integrity, and consistency.')
            ->onViolation('Block immediately. Use ' . VectorMemoryMcpTool::id() . ' instead.');

        $this->rule('memory-first-mandatory')->critical()
            ->text('ALL agents MUST search vector memory BEFORE task execution. NO exceptions.')
            ->why('Vector memory is PRIMARY knowledge base. Prevents duplicate work, enables learning reuse.')
            ->onViolation('Add pre-task memory search: ' . VectorMemoryMcpTool::call('search_memories', '{query: "{task}", limit: 5}'));

        $this->rule('store-learnings-mandatory')->high()
            ->text('Agents MUST store significant learnings, solutions, and insights after task completion.')
            ->why('Builds collective intelligence. Each agent contributes to shared knowledge base.')
            ->onViolation('Add post-task memory store: ' . VectorMemoryMcpTool::call('store_memory', '{content: "{insights}", category: "{category}", tags: [...]}'));

        $this->rule('agent-continuity')->high()
            ->text('In sequential multi-agent workflows, each agent MUST check memory for previous agents\' outputs.')
            ->why('Memory is communication channel between agents. Ensures context continuity.')
            ->onViolation('Include memory search in agent delegation instructions.');
    }
}
