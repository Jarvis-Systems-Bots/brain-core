<?php

declare(strict_types=1);

namespace BrainCore\Includes\Commands\Task;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;
use BrainCore\Compilation\BrainCLI;
use BrainCore\Compilation\Operator;
use BrainCore\Compilation\Store;
use BrainCore\Compilation\Tools\BashTool;
use BrainCore\Compilation\Tools\TaskTool;
use BrainNode\Mcp\SequentialThinkingMcp;
use BrainNode\Mcp\VectorMemoryMcp;
use BrainNode\Mcp\VectorTaskMcp;

#[Purpose('The task decomposition specialist analyzes the parent task, identifies potential decomposition patterns, and creates subtasks with estimated effort. Ensures each subtask meets the 5-8h golden rule through recursive analysis.')]
class TaskDecomposeInclude extends IncludeArchetype
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
            ->text('Task decomposition specialist that breaks down large tasks into atomic, manageable subtasks. Ensures each subtask meets the 5-8h golden rule through recursive analysis.');

        // Golden Rule (CRITICAL)
        $this->rule('golden-rule-estimate')->critical()
            ->text('Each subtask estimate MUST be <= 5-8 hours. This is the CORE PURPOSE of this command.')
            ->why('Tasks >8h are too large for effective tracking, estimation accuracy, and focus')
            ->onViolation('Decompose further until all subtasks meet 5-8h limit. Flag for recursive /task:decompose if needed.');

        // Iron Rules
        $this->rule('fetch-parent-first')->critical()
            ->text('MUST fetch and understand parent task via ' . VectorTaskMcp::call('task_get', '{task_id}') . ' BEFORE decomposing')
            ->why('Cannot decompose without full understanding of parent task scope and requirements')
            ->onViolation('Execute task_get first, analyze title, content, priority, and existing context');

        $this->rule('scan-materials')->critical()
            ->text('MUST scan ALL relevant materials for informed decomposition')
            ->why('Accurate decomposition requires understanding codebase, docs, and architecture context')
            ->onViolation('Use Explore agent + brain docs + vector memory before formulating subtasks');

        $this->rule('mandatory-user-approval')->critical()
            ->text('MUST get explicit user approval BEFORE creating any subtasks')
            ->why('User must validate decomposition strategy and subtask specifications before committing')
            ->onViolation('Present complete subtask list and wait for explicit YES/APPROVE/CONFIRM');

        $this->rule('correct-parent-id')->critical()
            ->text('MUST set parent_id = task_id for all created subtasks')
            ->why('Hierarchy integrity requires correct parent-child relationships')
            ->onViolation('Verify parent_id in every task_create call matches original task_id');

        $this->rule('flag-recursive-decompose')->high()
            ->text('MUST flag subtasks >8h for recursive decomposition with specific recommendation')
            ->why('Large subtasks defeat the purpose of decomposition')
            ->onViolation('Add explicit note: "Run /task:decompose {subtask_id}" for any subtask >8h');

        // Workflow Step 0 - Parse Arguments
        $this->guideline('workflow-step0')
            ->text('STEP 0 - Parse $ARGUMENTS and Extract task_id')
            ->example()
            ->phase('action-1', 'Extract task_id from $ARGUMENTS')
            ->phase('validate', Operator::validate(
                'task_id is numeric or valid identifier',
                'Request valid task_id from user'
            ))
            ->phase('output', Store::as('TASK_ID', 'extracted task_id'));

        // Workflow Step 1 - Fetch Parent Task
        $this->guideline('workflow-step1')
            ->text('STEP 1 - Fetch Parent Task Details')
            ->example()
            ->phase('fetch', VectorTaskMcp::call('task_get', '{task_id: ' . Store::get('TASK_ID') . '}'))
            ->phase('validate', Operator::validate(
                'Task exists and has content',
                'Report: Task not found. Verify task_id and try again.'
            ))
            ->phase('extract', 'Parse: title, content, priority, tags, current status, existing children')
            ->phase('output', Store::as('PARENT_TASK', '{title, content, priority, tags, status}'));

        // Workflow Step 2 - Search Vector Memory
        $this->guideline('workflow-step2')
            ->text('STEP 2 - Search Vector Memory for Decomposition Patterns')
            ->example()
            ->phase('search-patterns', VectorMemoryMcp::call('search_memories', '{query: "task decomposition ' . Store::get('PARENT_TASK') . '.domain patterns", limit: 5, category: "tool-usage"}'))
            ->phase('search-similar', VectorMemoryMcp::call('search_memories', '{query: "' . Store::get('PARENT_TASK') . '.title subtasks breakdown", limit: 3, category: "architecture"}'))
            ->phase('analyze', 'Extract: successful decomposition patterns, common subtask structures, pitfalls to avoid')
            ->phase('output', Store::as('PRIOR_PATTERNS', 'memory IDs, decomposition insights, recommendations'));

        // Workflow Step 3 - Scan Relevant Materials
        $this->guideline('workflow-step3')
            ->text('STEP 3 - Scan Relevant Materials Based on Task Scope')
            ->example()
            ->phase('explore-code', Operator::if(
                'parent task involves code changes',
                TaskTool::agent('explore', 'Scan codebase for ' . Store::get('PARENT_TASK') . '.domain. Find: affected components, dependencies, complexity areas. Return: file list, architecture notes, integration points'),
                Operator::skip('Code exploration not needed for non-code task')
            ))
            ->phase('scan-docs', BashTool::call(BrainCLI::DOCS, Store::get('PARENT_TASK') . '.domain') . ' → gather relevant documentation')
            ->phase('check-arch', Operator::if(
                'parent task involves architecture',
                VectorMemoryMcp::call('search_memories', '{query: "architecture ' . Store::get('PARENT_TASK') . '.domain", limit: 3, category: "architecture"}'),
                Operator::skip('Architecture search not needed')
            ))
            ->phase('output', Store::as('MATERIALS', 'code findings, doc references, architecture decisions'));

        // Workflow Step 4 - Deep Analysis via Sequential Thinking
        $this->guideline('workflow-step4')
            ->text('STEP 4 - Decomposition Analysis via Sequential Thinking')
            ->example()
            ->phase('thinking', SequentialThinkingMcp::call('sequentialthinking', '{
                    thought: "Analyzing decomposition strategy for: ' . Store::get('PARENT_TASK') . '.title. Golden rule: each subtask <=5-8h.",
                    thoughtNumber: 1,
                    totalThoughts: 5,
                    nextThoughtNeeded: true
                }'))
            ->phase('analyze-1', 'Identify natural task boundaries and logical groupings')
            ->phase('analyze-2', 'Map dependencies between potential subtasks')
            ->phase('analyze-3', 'Estimate effort for each subtask (MUST be <=5-8h)')
            ->phase('analyze-4', 'Determine optimal execution order based on dependencies')
            ->phase('analyze-5', 'Flag any subtask >8h for recursive decomposition')
            ->phase('output', Store::as('DECOMPOSITION_PLAN', 'subtask list with estimates, dependencies, order'));

        // Workflow Step 5 - Formulate Subtask Specifications
        $this->guideline('workflow-step5')
            ->text('STEP 5 - Formulate Subtask Specifications')
            ->example()
            ->phase('iterate', Operator::forEach(
                'subtask in ' . Store::get('DECOMPOSITION_PLAN'),
                [
                    'Create specification with:',
                    '  title: concise, action-oriented (max 6 words)',
                    '  content: scope, requirements, acceptance criteria, implementation guidance',
                    '  estimate: hours (MUST be <=5-8h)',
                    '  priority: inherit from parent or adjust based on dependencies',
                    '  tags: inherit parent tags + subtask-specific',
                    Operator::if(
                        'estimate > 8 hours',
                        'FLAG: Mark for recursive decomposition, add tag [needs-decomposition]'
                    )
                ]
            ))
            ->phase('output', Store::as('SUBTASK_SPECS', '[{title, content, estimate, priority, tags, needs_decomposition}]'));

        // Workflow Step 6 - Present for Approval (MANDATORY)
        $this->guideline('workflow-step6')
            ->text('STEP 6 - Present Subtasks for User Approval (MANDATORY GATE)')
            ->example()
            ->phase('header', 'Display decomposition summary:')
            ->phase('parent-info', '  Parent Task: ' . Store::get('PARENT_TASK') . '.title (ID: ' . Store::get('TASK_ID') . ')')
            ->phase('count', '  Subtasks Generated: {count}')
            ->phase('total-estimate', '  Total Estimate: {sum of estimates}h')
            ->phase('list', Operator::forEach(
                'subtask in ' . Store::get('SUBTASK_SPECS'),
                [
                    '---',
                    '#{index}. {subtask.title}',
                    '  Estimate: {subtask.estimate}h',
                    '  Priority: {subtask.priority}',
                    '  Tags: {subtask.tags}',
                    '  Scope: {subtask.content preview}',
                    Operator::if(
                        'subtask.needs_decomposition',
                        '  [!] NEEDS FURTHER DECOMPOSITION (>8h)'
                    )
                ]
            ))
            ->phase('prompt', 'Ask: "Create these subtasks? (yes/no/modify)"')
            ->phase('gate', Operator::validate(
                'User response is YES, APPROVE, CONFIRM, or Y',
                'Wait for explicit approval. Allow modifications if requested.'
            ));

        // Workflow Step 7 - Create Subtasks
        $this->guideline('workflow-step7')
            ->text('STEP 7 - Create Subtasks After Approval')
            ->example()
            ->phase('create-loop', Operator::forEach(
                'subtask in ' . Store::get('SUBTASK_SPECS'),
                [
                    VectorTaskMcp::call('task_create', '{
                            title: subtask.title,
                            content: subtask.content,
                            parent_id: ' . Store::get('TASK_ID') . ',
                            priority: subtask.priority,
                            tags: subtask.tags
                        }'),
                    Store::as('CREATED_SUBTASK_ID', 'response task_id'),
                    'Log: Created subtask #{index}: {title} (ID: ' . Store::get('CREATED_SUBTASK_ID') . ')'
                ]
            ))
            ->phase('output', Store::as('CREATED_SUBTASKS', '[{id, title, estimate, needs_decomposition}]'));

        // Workflow Step 8 - Post-Creation Summary
        $this->guideline('workflow-step8')
            ->text('STEP 8 - Post-Creation Summary and Recursive Recommendations')
            ->example()
            ->phase('summary', 'Report decomposition complete:')
            ->phase('count', '  Created {count} subtasks for task ID ' . Store::get('TASK_ID'))
            ->phase('ids', '  Subtask IDs: ' . Store::get('CREATED_SUBTASKS') . '.ids')
            ->phase('recursive-check', Operator::if(
                'any subtask has needs_decomposition = true',
                [
                    'RECURSIVE DECOMPOSITION NEEDED:',
                    Operator::forEach(
                        'subtask in ' . Store::get('CREATED_SUBTASKS') . ' where needs_decomposition',
                        '  - /task:decompose {subtask.id} (estimate: {subtask.estimate}h)'
                    )
                ]
            ))
            ->phase('next-steps', 'Suggest: /task:next to start working, /task:list to view hierarchy');

        // Workflow Step 9 - Store Decomposition Insight
        $this->guideline('workflow-step9')
            ->text('STEP 9 - Store Decomposition Approach to Vector Memory')
            ->example()
            ->phase('store', VectorMemoryMcp::call('store_memory', '{
                    content: "Decomposed task: ' . Store::get('PARENT_TASK') . '.title into {count} subtasks. Strategy: {approach summary}. Estimates: {estimate breakdown}. Domain: {domain}.",
                    category: "tool-usage",
                    tags: ["task-decomposition", "{domain}", "workflow-pattern"]
                }'));

        // Subtask Specification Format
        $this->guideline('subtask-format')
            ->text('Required subtask specification structure')
            ->example('Max 6 words, action-oriented, clear scope')->key('title')
            ->example('Scope, requirements, acceptance criteria, guidance. NO water.')->key('content')
            ->example('MUST be <=5-8h (GOLDEN RULE)')->key('estimate')
            ->example('Inherit from parent or adjust: critical|high|medium|low')->key('priority')
            ->example('Inherit parent tags + subtask-specific')->key('tags');

        // Estimation Guidelines for Decomposition
        $this->guideline('estimation-rules')
            ->text('Subtask estimation guidelines (GOLDEN RULE: <=5-8h)')
            ->example('1-2h: Config, simple edits, single file changes')->key('xs')
            ->example('2-4h: Small features, multi-file, simple tests')->key('s')
            ->example('4-6h: Moderate features, refactoring, integrations')->key('m')
            ->example('6-8h: Complex single feature, architectural piece')->key('l')
            ->example('>8h: VIOLATION - decompose further immediately')->key('violation');

        // Decomposition Strategies
        $this->guideline('decomposition-strategies')
            ->text('Common decomposition patterns')
            ->example('Split by layer: API, service, repository, tests')->key('layered')
            ->example('Split by feature: auth, validation, core, UI')->key('feature')
            ->example('Split by phase: research, implement, test, document')->key('phase')
            ->example('Split by dependency: independent first, dependent after')->key('dependency')
            ->example('Split by risk: high-risk isolated for focused testing')->key('risk');

        // Quality Gates
        $this->guideline('quality-gates')
            ->text('Validation checkpoints before subtask creation')
            ->example('Parent task fetched and fully understood')
            ->example('Vector memory searched for decomposition patterns')
            ->example('Relevant materials scanned (code, docs, architecture)')
            ->example('Sequential thinking analysis completed')
            ->example('ALL subtasks have estimate <=5-8h (GOLDEN RULE)')
            ->example('Dependencies between subtasks identified')
            ->example('User approval explicitly received')
            ->example('Subtasks >8h flagged for recursive decomposition');

        // Directive
        $this->guideline('directive')
            ->text('Fetch. Research. Analyze. Decompose. Validate estimates. Get approval. Create. Flag recursive.');
    }
}
