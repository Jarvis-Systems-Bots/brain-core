<?php

declare(strict_types=1);

namespace BrainCore\Includes\Commands;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;
use BrainCore\Compilation\BrainCLI;
use BrainCore\Compilation\Operator;
use BrainCore\Compilation\Store;
use BrainCore\Compilation\Tools\BashTool;
use BrainCore\Compilation\Tools\ReadTool;
use BrainCore\Compilation\Tools\TaskTool;
use BrainNode\Mcp\SequentialThinkingMcp;
use BrainNode\Mcp\VectorTaskMcp;

#[Purpose('Project task initializer that scans all available project materials, analyzes scope and requirements, decomposes work into manageable root tasks, and creates task hierarchy after user approval.')]
class InitTaskInclude extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     *
     * @return void
     */
    protected function handle(): void
    {
        // Role definition
        $this->guideline('role')
            ->text('Project task initializer that scans all available project materials, analyzes scope and requirements, decomposes work into manageable root tasks, and creates task hierarchy after user approval.');

        // Iron Rules
        $this->rule('scan-all-docs-first')->critical()
            ->text('MUST scan ALL available documentation before generating tasks')
            ->why('Comprehensive understanding prevents missed requirements and duplicate work')
            ->onViolation('Execute full scanning workflow before task generation');

        $this->rule('mandatory-user-approval')->critical()
            ->text('MUST get explicit user approval before creating ANY tasks')
            ->why('User must validate task breakdown before committing to vector storage')
            ->onViolation('Present task list and wait for user confirmation');

        $this->rule('estimate-required')->critical()
            ->text('MUST provide time estimate for EACH task')
            ->why('Estimates enable planning and identify tasks needing decomposition')
            ->onViolation('Add estimate before presenting task');

        $this->rule('max-task-estimate')->high()
            ->text('Each task estimate MUST be 5-8 hours maximum')
            ->why('Larger tasks should be decomposed for manageability')
            ->onViolation('Recommend /task:decompose for tasks >8h');

        $this->rule('no-creation-without-confirmation')->critical()
            ->text('NO task creation without explicit user YES/APPROVE/CONFIRM')
            ->why('Prevents accidental task creation and allows user revision')
            ->onViolation('Wait for explicit approval signal');

        // Workflow Step 0 - Check existing tasks
        $this->guideline('workflow-step0')
            ->text('STEP 0 - Check Existing Tasks')
            ->example()
            ->phase('action-1', VectorTaskMcp::call('task_stats', '{}') . ' → check if tasks already exist')
            ->phase('decision', Operator::if(
                'existing tasks > 0',
                'WARN user about existing tasks, ask to continue or abort',
                'Proceed with initialization'
            ));

        // Workflow Step 1 - Scan Documentation
        $this->guideline('workflow-step1')
            ->text('STEP 1 - Scan All Documentation')
            ->example()
            ->phase('action-1', BashTool::call(BrainCLI::DOCS) . ' → ' . Store::as('DOCS_INDEX', 'all indexed documentation'))
            ->phase('action-2', ReadTool::call('README.md') . ' → ' . Store::as('README', 'project overview'))
            ->phase('action-3', Operator::forEach(
                Store::get('DOCS_INDEX') . ' paths',
                ReadTool::call('{doc_path}') . ' → accumulate key insights'
            ))
            ->phase('output', Store::as('PROJECT_DOCS', 'consolidated documentation insights'));

        // Workflow Step 2 - Explore Codebase
        $this->guideline('workflow-step2')
            ->text('STEP 2 - Explore Codebase Structure')
            ->example()
            ->phase('action-1', TaskTool::agent('explore', 'Analyze project structure: directories, key files, architecture patterns, tech stack, dependencies. Return: structured summary with component list'))
            ->phase('output', Store::as('CODEBASE_STRUCTURE', 'architecture and component analysis'));

        // Workflow Step 3 - Analyze Context
        $this->guideline('workflow-step3')
            ->text('STEP 3 - Synthesize Project Context')
            ->example()
            ->phase('analyze-1', 'Extract: project scope, primary objectives, tech stack')
            ->phase('analyze-2', 'Identify: requirements, constraints, dependencies')
            ->phase('analyze-3', 'Assess: current state (greenfield/existing/refactor)')
            ->phase('output', Store::as('PROJECT_CONTEXT', 'scope, stack, requirements, constraints, state'));

        // Workflow Step 4 - Task Decomposition Thinking
        $this->guideline('workflow-step4')
            ->text('STEP 4 - Strategic Task Decomposition')
            ->example()
            ->phase('thinking', SequentialThinkingMcp::call('sequentialthinking', '{
                    thought: "Analyzing project context for task decomposition",
                    thoughtNumber: 1,
                    totalThoughts: 5,
                    nextThoughtNeeded: true
                }'))
            ->phase('decompose-1', 'Identify major work streams from requirements')
            ->phase('decompose-2', 'Break each stream into root-level tasks')
            ->phase('decompose-3', 'Estimate each task (hours)')
            ->phase('decompose-4', 'Assign priority: critical > high > medium > low')
            ->phase('decompose-5', 'Add relevant tags for categorization')
            ->phase('output', Store::as('TASK_LIST', 'array of {title, content, priority, estimate, tags}'));

        // Workflow Step 5 - Present for Approval
        $this->guideline('workflow-step5')
            ->text('STEP 5 - Present Task List for User Approval (MANDATORY GATE)')
            ->example()
            ->phase('format', 'Format task list as numbered table: # | Title | Priority | Estimate | Tags')
            ->phase('summary', 'Show: total tasks, total estimated hours, tasks >8h needing decomposition')
            ->phase('recommendations', Operator::if(
                'any task estimate > 8h',
                'List tasks recommended for /task:decompose after creation'
            ))
            ->phase('prompt', 'Ask user: "Approve task creation? (yes/no/modify)"')
            ->phase('gate', Operator::validate(
                'User response is YES, APPROVE, or CONFIRM',
                'Wait for explicit approval, allow modifications'
            ));

        // Workflow Step 6 - Create Tasks
        $this->guideline('workflow-step6')
            ->text('STEP 6 - Create Tasks After Approval')
            ->example()
            ->phase('decision', Operator::if(
                'task count <= 5',
                Operator::forEach('task in ' . Store::get('TASK_LIST'), VectorTaskMcp::call('task_create', '{title, content, priority, tags}')),
                VectorTaskMcp::call('task_create_bulk', '{tasks: ' . Store::get('TASK_LIST') . '}')
            ))
            ->phase('verify', VectorTaskMcp::call('task_stats', '{}') . ' → confirm creation');

        // Workflow Step 7 - Summary
        $this->guideline('workflow-step7')
            ->text('STEP 7 - Report Summary')
            ->example()
            ->phase('report-1', 'List created tasks with IDs')
            ->phase('report-2', 'Total estimated hours')
            ->phase('report-3', 'Recommended next: /task:decompose for large tasks')
            ->phase('report-4', 'Suggest: /task:next to start working');

        // Task Format Specification
        $this->guideline('task-format')
            ->text('Required task structure for creation')
            ->example('title: Concise task name (max 10 words)')->key('title')
            ->example('content: Detailed description with acceptance criteria')->key('content')
            ->example('priority: critical | high | medium | low')->key('priority')
            ->example('tags: [category, stack, area]')->key('tags')
            ->example('estimate: N hours (internal tracking, not stored)')->key('estimate');

        // Estimation Guidelines
        $this->guideline('estimation-rules')
            ->text('Task estimation guidelines')
            ->example('1-2h: Simple changes, single-file edits, config updates')->key('xs')
            ->example('2-4h: Feature additions, multi-file changes, tests')->key('s')
            ->example('4-6h: Complex features, refactoring, integrations')->key('m')
            ->example('6-8h: Major features, architectural changes')->key('l')
            ->example('>8h: Needs decomposition via /task:decompose')->key('xl');

        // Priority Assignment
        $this->guideline('priority-assignment')
            ->text('Priority assignment criteria')
            ->example('critical: Blockers, security, data integrity, core functionality')->key('critical')
            ->example('high: Key features, dependencies for other tasks, deadlines')->key('high')
            ->example('medium: Standard features, improvements, optimizations')->key('medium')
            ->example('low: Nice-to-have, cosmetic, documentation, cleanup')->key('low');

        // Quality Gates
        $this->guideline('quality-gates')
            ->text('Validation checkpoints')
            ->example('All documentation scanned')
            ->example('Codebase structure analyzed')
            ->example('Each task has: title, content, priority, estimate, tags')
            ->example('No task estimate exceeds 8h without decomposition note')
            ->example('User approval received before creation')
            ->example('Task stats verified after creation');
    }
}
