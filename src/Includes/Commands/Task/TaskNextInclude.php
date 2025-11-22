<?php

declare(strict_types=1);

namespace BrainCore\Includes\Commands\Task;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;
use BrainCore\Compilation\Operator;
use BrainCore\Compilation\Store;
use BrainNode\Mcp\VectorMemoryMcp;
use BrainNode\Mcp\VectorTaskMcp;

#[Purpose('The next task selection specialist uses MCP to identify the next task to work on. Includes task details, parent hierarchy, and related vector memory insights.')]
class TaskNextInclude extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     *
     * @return void
     */
    protected function handle(): void
    {
        // Workflow Step 1 - Get Next Task
        $this->guideline('workflow-step1')
            ->text('STEP 1 - Get Next Task via MCP')
            ->example()
            ->phase('action', VectorTaskMcp::call('task_next', '{}'))
            ->phase('store', Store::as('NEXT_TASK', 'task object or null'));

        // Workflow Step 2 - Handle No Task
        $this->guideline('workflow-step2')
            ->text('STEP 2 - Handle No Tasks Available')
            ->example()
            ->phase('check', Operator::if(
                Store::get('NEXT_TASK') . ' is null or empty',
                Operator::do(
                    'Display: "No pending tasks available."',
                    'Suggest: "Use /task:init to initialize project tasks"',
                    'Suggest: "Use /task:create {description} to add a new task"',
                    Operator::skip('No task to display')
                )
            ));

        // Workflow Step 3 - Show Task Details
        $this->guideline('workflow-step3')
            ->text('STEP 3 - Display Task Details')
            ->example()
            ->phase('display-1', 'Task ID: {id}')
            ->phase('display-2', 'Status: {status} (in_progress or pending)')
            ->phase('display-3', 'Title: {title}')
            ->phase('display-4', 'Priority: {priority}')
            ->phase('display-5', 'Tags: {tags}')
            ->phase('display-6', 'Content: {content}');

        // Workflow Step 4 - Show Parent Hierarchy (if exists)
        $this->guideline('workflow-step4')
            ->text('STEP 4 - Show Parent Hierarchy (if parent_id exists)')
            ->example()
            ->phase('check', Operator::if(
                Store::get('NEXT_TASK') . '.parent_id is not null',
                Operator::do(
                    VectorTaskMcp::call('task_get', '{task_id: ' . Store::get('NEXT_TASK') . '.parent_id}'),
                    Store::as('PARENT_TASK', 'parent task object'),
                    'Display: "Parent Task: {parent.title} (ID: {parent.id})"'
                ),
                Operator::skip('No parent task')
            ));

        // Workflow Step 5 - Search Related Memory Insights
        $this->guideline('workflow-step5')
            ->text('STEP 5 - Search Vector Memory for Related Insights')
            ->example()
            ->phase('search', VectorMemoryMcp::call('search_memories', '{query: "' . Store::get('NEXT_TASK') . '.title", limit: 3}'))
            ->phase('display', Operator::if(
                'memories found',
                'Display: "Related Insights:" followed by memory summaries',
                Operator::skip('No related insights')
            ));

        // Workflow Step 6 - Suggest Next Actions
        $this->guideline('workflow-step6')
            ->text('STEP 6 - Suggest Next Actions')
            ->example()
            ->phase('suggest-1', Operator::if(
                Store::get('NEXT_TASK') . '.status is pending',
                'Suggest: "Start working? Use /do:async or /do:sync to execute this task"'
            ))
            ->phase('suggest-2', Operator::if(
                Store::get('NEXT_TASK') . '.status is in_progress',
                'Suggest: "Continue working on this task. Use /do:async or /do:sync"'
            ))
            ->phase('suggest-3', 'Suggest: "Use /task:decompose {id} if task needs breakdown"');

        // Output Format
        $this->guideline('output-format')
            ->text('Display format for task details')
            ->example('## Next Task')->key('header')
            ->example('ID: {id} | Status: {status} | Priority: {priority}')->key('meta')
            ->example('Title: {title}')->key('title')
            ->example('Tags: {tags joined}')->key('tags')
            ->example('Content: {content}')->key('content')
            ->example('Parent: {parent.title} (optional)')->key('parent')
            ->example('Related Insights: {memories} (optional)')->key('insights')
            ->example('Ready to execute? Use /do:async or /do:sync')->key('cta');
    }
}
