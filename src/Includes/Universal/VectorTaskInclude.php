<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;
use BrainNode\Mcp\VectorTaskMcp;

#[Purpose(<<<'PURPOSE'
Vector task MCP protocol for hierarchical task management.
Task-first workflow: LIST → EXECUTE → UPDATE.
Supports unlimited nesting via parent_id for flexible decomposition.
PURPOSE
)]
class VectorTaskInclude extends IncludeArchetype
{
    protected function handle(): void
    {
        // Task-First Protocol
        $this->guideline('task-first-workflow')
            ->text('Universal workflow: LIST → EXECUTE → UPDATE.')
            ->example()
            ->phase('pre-task', VectorTaskMcp::call('task_next', '{}') . ' → STORE-AS($CURRENT)')
            ->phase('start', VectorTaskMcp::call('task_start', '{task_id: $CURRENT.id}'))
            ->phase('execute', 'Perform task work, add comments for progress')
            ->phase('complete', VectorTaskMcp::call('task_finish', '{task_id: $CURRENT.id}'));

        // MCP Tools Reference
        $this->guideline('mcp-tools')
            ->text('Vector task MCP tools.')
            ->example(VectorTaskMcp::call('task_create', '{title, content, parent_id?, priority?, tags?}') . ' - Create task')->key('create')
            ->example(VectorTaskMcp::call('task_create_bulk', '{tasks: [...]}') . ' - Bulk create')->key('bulk')
            ->example(VectorTaskMcp::call('task_list', '{query?, status?, parent_id?, tags?, limit?, offset?}') . ' - Search/filter')->key('list')
            ->example(VectorTaskMcp::call('task_get', '{task_id}') . ' - Get by ID')->key('get')
            ->example(VectorTaskMcp::call('task_next', '{}') . ' - Smart selection: in_progress or next pending')->key('next')
            ->example(VectorTaskMcp::call('task_start', '{task_id}') . ' - Set in_progress')->key('start')
            ->example(VectorTaskMcp::call('task_stop', '{task_id}') . ' - Pause task')->key('stop')
            ->example(VectorTaskMcp::call('task_finish', '{task_id}') . ' - Complete task')->key('finish')
            ->example(VectorTaskMcp::call('task_update', '{task_id, title?, content?, status?, parent_id?, priority?, tags?}') . ' - Update')->key('update')
            ->example(VectorTaskMcp::call('task_comment', '{task_id, comment, append?}') . ' - Add comment')->key('comment')
            ->example(VectorTaskMcp::call('task_stats', '{}') . ' - Statistics')->key('stats');

        // Hierarchy via parent_id
        $this->guideline('hierarchy')
            ->text('Flexible hierarchy via parent_id. Unlimited nesting depth.')
            ->example('parent_id: null → root task (goal, milestone, epic)')->key('root')
            ->example('parent_id: N → child of task N (subtask, step, action)')->key('child')
            ->example('Depth determined by parent chain, not fixed levels')->key('depth')
            ->example('Naming convention optional: use tags for categorization')->key('naming');

        // Decomposition
        $this->guideline('decomposition')
            ->text('Break large tasks into manageable children.')
            ->example()
            ->phase('when', 'Task too complex for single execution')
            ->phase('how', 'Create children with parent_id = current task')
            ->phase('criteria', 'Logical separation, dependencies, parallel capability')
            ->phase('stop', 'When leaf task is atomic and executable');

        // Status Flow
        $this->guideline('status-flow')
            ->text('Task status lifecycle.')
            ->example('pending → in_progress → completed')->key('happy')
            ->example('pending → in_progress → stopped → in_progress → completed')->key('paused')
            ->example('Only ONE task in_progress at a time per agent')->key('rule');

        // Priority
        $this->guideline('priority')
            ->text('Priority levels: critical > high > medium > low.')
            ->example('Children inherit parent priority unless overridden')->key('inherit')
            ->example('Default: medium')->key('default');

        // Critical Rules
        $this->rule('mcp-only-access')->critical()
            ->text('ALL task operations MUST use MCP tools.')
            ->why('MCP ensures embedding generation and data integrity.')
            ->onViolation('Use ' . VectorTaskMcp::id() . ' tools.');

        $this->rule('single-in-progress')->high()
            ->text('Only ONE task should be in_progress at a time per agent.')
            ->why('Prevents context switching and ensures focus.')
            ->onViolation(VectorTaskMcp::call('task_finish', '{task_id}') . ' current before starting new.');

        $this->rule('parent-child-integrity')->high()
            ->text('Parent cannot be completed while children are pending/in_progress.')
            ->why('Ensures hierarchical consistency.')
            ->onViolation('Complete or stop all children first.');
    }
}
