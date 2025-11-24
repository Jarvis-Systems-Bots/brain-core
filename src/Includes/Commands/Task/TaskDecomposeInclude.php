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

#[Purpose('Task decomposition specialist that performs exhaustive research via agents, analyzes parent task deeply, and creates optimal subtasks meeting the 5-8h golden rule. NEVER executes tasks - only creates subtasks.')]
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
            ->text('Task decomposition specialist that orchestrates comprehensive research via agents, analyzes parent task deeply, and creates optimal subtasks. Ensures each subtask meets the 5-8h golden rule. This is CRITICAL for project manageability.');

        // Golden Rule (CRITICAL)
        $this->rule('golden-rule-estimate')->critical()
            ->text('Each subtask estimate MUST be <= 5-8 hours. This is the CORE PURPOSE of this command.')
            ->why('Tasks >8h are too large for effective tracking, estimation accuracy, and focus')
            ->onViolation('Decompose further until all subtasks meet 5-8h limit. Flag for recursive /task:decompose if needed.');

        // Iron Rules
        $this->rule('create-only-no-execution')->critical()
            ->text('This command ONLY creates subtasks. NEVER execute any subtask after creation, regardless of simplicity.')
            ->why('Task decomposition and task execution are separate concerns. User decides when to execute via /task:next or /do.')
            ->onViolation('STOP immediately after subtask creation. Return control to user.');

        $this->rule('delegate-research-to-agents')->critical()
            ->text('MUST delegate research to specialized agents. Direct scanning misses critical decomposition context.')
            ->why('Agents have deep exploration capabilities. Quality decomposition requires comprehensive understanding.')
            ->onViolation('Use Task(@agent-explore) for codebase analysis before formulating subtasks.');

        $this->rule('exhaustive-research-mandatory')->critical()
            ->text('MUST complete ALL research phases: parent task, vector memory, codebase (if code), documentation.')
            ->why('Poor decomposition from incomplete research creates wrong subtasks and wasted effort.')
            ->onViolation('STOP. Execute all research steps before proceeding to analysis.');

        $this->rule('fetch-parent-first')->critical()
            ->text('MUST fetch and understand parent task via ' . VectorTaskMcp::call('task_get', '{task_id}') . ' BEFORE decomposing')
            ->why('Cannot decompose without full understanding of parent task scope and requirements')
            ->onViolation('Execute task_get first, analyze title, content, priority, and existing context');

        $this->rule('check-existing-subtasks')->critical()
            ->text('MUST check for existing subtasks before creating new ones.')
            ->why('Prevents duplicate subtasks and identifies partial decomposition.')
            ->onViolation('Execute ' . VectorTaskMcp::call('task_list', '{parent_id: task_id}') . ' and analyze existing children.');

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

        // ============================================
        // PHASE 1: FETCH AND VALIDATE
        // ============================================

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
            ->text('STEP 1 - Fetch Parent Task Details (MANDATORY)')
            ->example()
            ->phase('fetch', VectorTaskMcp::call('task_get', '{task_id: ' . Store::get('TASK_ID') . '}'))
            ->phase('validate', Operator::validate(
                'Task exists and has content',
                'Report: Task not found. Verify task_id and try again.'
            ))
            ->phase('extract', 'Parse: title, content, priority, tags, current status')
            ->phase('output', Store::as('PARENT_TASK', '{title, content, priority, tags, status, estimate}'));

        // Workflow Step 2 - Check Existing Subtasks
        $this->guideline('workflow-step2')
            ->text('STEP 2 - Check for Existing Subtasks (MANDATORY)')
            ->example()
            ->phase('action-1', VectorTaskMcp::call('task_list', '{parent_id: ' . Store::get('TASK_ID') . ', limit: 50}'))
            ->phase('decision', Operator::if(
                'existing subtasks > 0',
                'WARN user: "Task already has {count} subtasks. Options: (1) Add more subtasks, (2) Replace all, (3) Abort"',
                'Continue with decomposition'
            ))
            ->phase('output', Store::as('EXISTING_SUBTASKS', 'list of existing subtask IDs and titles'));

        // ============================================
        // PHASE 2: EXHAUSTIVE RESEARCH (via Agents)
        // ============================================

        // Workflow Step 3 - Deep Vector Memory Search
        $this->guideline('workflow-step3')
            ->text('STEP 3 - Deep Vector Memory Search (MANDATORY)')
            ->example()
            ->phase('search-1', VectorMemoryMcp::call('search_memories', '{query: "task decomposition ' . Store::get('PARENT_TASK') . '.domain patterns", limit: 5, category: "tool-usage"}'))
            ->phase('search-2', VectorMemoryMcp::call('search_memories', '{query: "' . Store::get('PARENT_TASK') . '.title implementation breakdown", limit: 5, category: "architecture"}'))
            ->phase('search-3', VectorMemoryMcp::call('search_memories', '{query: "' . Store::get('PARENT_TASK') . '.domain subtasks structure", limit: 3, category: "code-solution"}'))
            ->phase('search-4', VectorMemoryMcp::call('search_memories', '{query: "' . Store::get('PARENT_TASK') . '.domain lessons learned mistakes", limit: 3, category: "learning"}'))
            ->phase('analyze', 'Extract: decomposition patterns, common structures, pitfalls, past estimates accuracy')
            ->phase('output', Store::as('PRIOR_PATTERNS', 'memory IDs, decomposition insights, recommendations, warnings'));

        // Workflow Step 4 - Codebase Exploration (Agent-Delegated)
        $this->guideline('workflow-step4')
            ->text('STEP 4 - Codebase Exploration via Explore Agent (MANDATORY for code tasks)')
            ->example()
            ->phase('decision', Operator::if(
                'parent task involves code changes (feature, bugfix, refactor)',
                Operator::task(
                    TaskTool::agent('explore', 'DECOMPOSITION ANALYSIS for task: "' . Store::get('PARENT_TASK') . '.title". Scan: affected components, file boundaries, dependencies, complexity areas, test requirements. Return: component breakdown, file groupings, integration points, suggested split boundaries.'),
                    'Wait for Explore agent comprehensive analysis'
                ),
                Operator::skip('Task is not code-related')
            ))
            ->phase('output', Store::as('CODEBASE_ANALYSIS', 'component breakdown, file groupings, complexity assessment'));

        // Workflow Step 5 - Documentation Research
        $this->guideline('workflow-step5')
            ->text('STEP 5 - Documentation Research (if relevant)')
            ->example()
            ->phase('action-1', BashTool::call(BrainCLI::DOCS, Store::get('PARENT_TASK') . '.domain') . ' → gather relevant documentation')
            ->phase('action-2', Operator::if(
                'parent task involves architecture or API',
                VectorMemoryMcp::call('search_memories', '{query: "architecture ' . Store::get('PARENT_TASK') . '.domain decisions", limit: 3, category: "architecture"}'),
                Operator::skip('Architecture search not needed')
            ))
            ->phase('output', Store::as('DOC_CONTEXT', 'documentation references, API specs, architectural decisions'));

        // ============================================
        // PHASE 3: ANALYSIS AND DECOMPOSITION
        // ============================================

        // Workflow Step 6 - Deep Analysis via Sequential Thinking
        $this->guideline('workflow-step6')
            ->text('STEP 6 - Decomposition Analysis via Sequential Thinking')
            ->example()
            ->phase('thinking', SequentialThinkingMcp::call('sequentialthinking', '{
                    thought: "Analyzing decomposition strategy for: ' . Store::get('PARENT_TASK') . '.title. Context: ' . Store::get('CODEBASE_ANALYSIS') . '. Golden rule: each subtask <=5-8h.",
                    thoughtNumber: 1,
                    totalThoughts: 5,
                    nextThoughtNeeded: true
                }'))
            ->phase('analyze-1', 'Identify natural task boundaries from ' . Store::get('CODEBASE_ANALYSIS'))
            ->phase('analyze-2', 'Map dependencies between potential subtasks')
            ->phase('analyze-3', 'Estimate effort for each subtask (MUST be <=5-8h)')
            ->phase('analyze-4', 'Determine optimal execution order based on dependencies')
            ->phase('analyze-5', 'Flag any subtask >8h for recursive decomposition')
            ->phase('output', Store::as('DECOMPOSITION_PLAN', 'subtask list with estimates, dependencies, order'));

        // Workflow Step 7 - Formulate Subtask Specifications
        $this->guideline('workflow-step7')
            ->text('STEP 7 - Formulate Subtask Specifications')
            ->example()
            ->phase('iterate', Operator::forEach(
                'subtask in ' . Store::get('DECOMPOSITION_PLAN'),
                [
                    'Create specification with:',
                    '  title: concise, action-oriented (max 6 words)',
                    '  content: scope, requirements, acceptance criteria, implementation guidance from ' . Store::get('CODEBASE_ANALYSIS'),
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

        // ============================================
        // PHASE 4: USER APPROVAL AND CREATION
        // ============================================

        // Workflow Step 8 - Present for Approval (MANDATORY)
        $this->guideline('workflow-step8')
            ->text('STEP 8 - Present Subtasks for User Approval (MANDATORY GATE)')
            ->example()
            ->phase('header', 'Display decomposition summary:')
            ->phase('parent-info', '  Parent Task: ' . Store::get('PARENT_TASK') . '.title (ID: ' . Store::get('TASK_ID') . ')')
            ->phase('existing', '  Existing Subtasks: ' . Store::get('EXISTING_SUBTASKS'))
            ->phase('count', '  New Subtasks: {count}')
            ->phase('total-estimate', '  Total Estimate: {sum of estimates}h')
            ->phase('research-sources', '  Research: Memory ({pattern_count}), Codebase ({file_count} files), Docs')
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

        // Workflow Step 9 - Create Subtasks
        $this->guideline('workflow-step9')
            ->text('STEP 9 - Create Subtasks After Approval')
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

        // Workflow Step 10 - Post-Creation Summary (END - NO EXECUTION)
        $this->guideline('workflow-step10')
            ->text('STEP 10 - Post-Creation Summary (END - NO EXECUTION)')
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
            ->phase('next-steps', 'NEXT STEPS:')
            ->phase('next-1', '  1. /task:decompose {id} - for any subtask >8h')
            ->phase('next-2', '  2. /task:list --parent=' . Store::get('TASK_ID') . ' - view subtask hierarchy')
            ->phase('next-3', '  3. /task:next - start working on first subtask')
            ->phase('stop', 'STOP HERE. Do NOT execute any subtask. Return control to user.');

        // Workflow Step 11 - Store Decomposition Insight
        $this->guideline('workflow-step11')
            ->text('STEP 11 - Store Decomposition Approach to Vector Memory')
            ->example()
            ->phase('store', VectorMemoryMcp::call('store_memory', '{
                    content: "Decomposed task: ' . Store::get('PARENT_TASK') . '.title into {count} subtasks. Strategy: {approach}. Estimates: {breakdown}. Files: {files from ' . Store::get('CODEBASE_ANALYSIS') . '}.",
                    category: "tool-usage",
                    tags: ["task-decomposition", "{domain}", "workflow-pattern"]
                }'));

        // ============================================
        // REFERENCE: FORMATS AND GUIDELINES
        // ============================================

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
            ->text('ALL quality gates MUST pass before subtask creation')
            ->example('Step 0: task_id extracted and validated')
            ->example('Step 1: Parent task fetched and fully understood')
            ->example('Step 2: Existing subtasks checked (avoid duplicates)')
            ->example('Step 3: Vector memory searched (4 categories)')
            ->example('Step 4: Codebase explored via Explore agent (if code task)')
            ->example('Step 5: Documentation researched')
            ->example('Step 6: Sequential thinking decomposition completed')
            ->example('Step 7: ALL subtasks have estimate <=5-8h (GOLDEN RULE)')
            ->example('Step 8: User approval explicitly received')
            ->example('Step 10: STOP after creation - do NOT execute');

        // Directive
        $this->guideline('directive')
            ->text('Fetch. Check existing. Research deeply. Analyze. Decompose. Validate estimates. Get approval. Create. STOP.');
    }
}
