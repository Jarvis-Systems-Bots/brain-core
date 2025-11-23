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

#[Purpose('Task creation specialist that analyzes user descriptions, researches context, estimates effort, and creates well-structured tasks after user approval.')]
class TaskCreateInclude extends IncludeArchetype
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
            ->text('Task creation specialist that analyzes user descriptions, researches context, estimates effort, and creates well-structured tasks after user approval.');

        // Iron Rules
        $this->rule('analyze-arguments')->critical()
            ->text('MUST analyze $ARGUMENTS thoroughly before creating any task')
            ->why('User description requires deep understanding to create accurate task specification')
            ->onViolation('Parse and analyze $ARGUMENTS first, extract scope, requirements, and context');

        $this->rule('search-memory-first')->critical()
            ->text('MUST search vector memory for similar past work before analysis')
            ->why('Prevents duplicate work and leverages existing insights')
            ->onViolation('Execute ' . VectorMemoryMcp::call('search_memories', '{query: "{task_domain}", limit: 5}'));

        $this->rule('estimate-required')->critical()
            ->text('MUST provide time estimate for the task')
            ->why('Estimates enable planning and identify tasks needing decomposition')
            ->onViolation('Add estimate in hours before presenting task');

        $this->rule('mandatory-user-approval')->critical()
            ->text('MUST get explicit user approval BEFORE creating any task')
            ->why('User must validate task specification before committing to vector storage')
            ->onViolation('Present task specification and wait for explicit YES/APPROVE/CONFIRM');

        $this->rule('max-task-estimate')->high()
            ->text('If estimate >5-8 hours, MUST strongly recommend /task:decompose')
            ->why('Large tasks should be decomposed for better manageability and tracking')
            ->onViolation('Warn user and recommend decomposition after task creation');

        $this->rule('create-only-no-execution')->critical()
            ->text('This command ONLY creates tasks. NEVER execute the task after creation, regardless of size or complexity.')
            ->why('Task creation and task execution are separate concerns. User decides when to execute via /task:next or /do commands.')
            ->onViolation('STOP immediately. Return created task ID and let user decide next action.');

        // Workflow Step 0 - Parse Arguments
        $this->guideline('workflow-step0')
            ->text('STEP 0 - Parse and Understand $ARGUMENTS')
            ->example()
            ->phase('action-1', 'Extract: primary objective, scope, requirements from user description')
            ->phase('action-2', 'Identify: implicit constraints, technical domain, affected areas')
            ->phase('action-3', 'Determine: task type (feature, bugfix, refactor, research, docs)')
            ->phase('output', Store::as('TASK_SCOPE', 'parsed objective, domain, requirements, type'));

        // Workflow Step 1 - Search Vector Memory
        $this->guideline('workflow-step1')
            ->text('STEP 1 - Search Vector Memory for Similar Work')
            ->example()
            ->phase('action-1', VectorMemoryMcp::call('search_memories', '{query: "{task_domain} {objective}", limit: 5, category: "code-solution"}'))
            ->phase('action-2', VectorMemoryMcp::call('search_memories', '{query: "{task_domain} implementation", limit: 3, category: "architecture"}'))
            ->phase('analyze', 'Extract: relevant insights, reusable patterns, approaches to avoid')
            ->phase('output', Store::as('PRIOR_WORK', 'memory IDs, insights, recommendations'));

        // Workflow Step 2 - Context Analysis (conditional)
        $this->guideline('workflow-step2')
            ->text('STEP 2 - Gather Additional Context (if needed)')
            ->example()
            ->phase('decision-code', Operator::if(
                'task is code-related',
                TaskTool::agent('explore', 'Scan codebase for {domain}. Find: existing components, patterns, dependencies. Return: relevant files, architecture notes'),
                Operator::skip('Code exploration not needed')
            ))
            ->phase('decision-docs', Operator::if(
                'task is architecture-related',
                BashTool::call(BrainCLI::DOCS, '{domain}') . ' → scan relevant documentation',
                Operator::skip('Documentation scan not needed')
            ))
            ->phase('output', Store::as('CONTEXT', 'codebase findings, documentation references'));

        // Workflow Step 3 - Deep Analysis
        $this->guideline('workflow-step3')
            ->text('STEP 3 - Task Analysis via Sequential Thinking')
            ->example()
            ->phase('thinking', SequentialThinkingMcp::call('sequentialthinking', '{
                    thought: "Analyzing task scope, complexity, and requirements for: ' . Store::get('TASK_SCOPE') . '",
                    thoughtNumber: 1,
                    totalThoughts: 4,
                    nextThoughtNeeded: true
                }'))
            ->phase('analyze-1', 'Assess complexity: simple (1-2h), moderate (2-4h), complex (4-6h), major (6-8h), decompose (>8h)')
            ->phase('analyze-2', 'Identify: dependencies, blockers, prerequisites')
            ->phase('analyze-3', 'Determine: priority based on urgency and impact')
            ->phase('analyze-4', 'Extract: acceptance criteria from requirements')
            ->phase('output', Store::as('ANALYSIS', 'complexity, estimate, priority, dependencies, criteria'));

        // Workflow Step 4 - Formulate Task Specification
        $this->guideline('workflow-step4')
            ->text('STEP 4 - Formulate Task Specification')
            ->example()
            ->phase('title', 'Create concise title (max 10 words) capturing objective')
            ->phase('content', 'Write detailed description with: objective, context, acceptance criteria, implementation hints')
            ->phase('priority', 'Assign: critical | high | medium | low')
            ->phase('tags', 'Add relevant tags: [category, domain, stack]')
            ->phase('estimate', 'Set time estimate in hours')
            ->phase('output', Store::as('TASK_SPEC', '{title, content, priority, tags, estimate}'));

        // Workflow Step 5 - Present for Approval (MANDATORY)
        $this->guideline('workflow-step5')
            ->text('STEP 5 - Present Task for User Approval (MANDATORY GATE)')
            ->example()
            ->phase('present-1', 'Display task specification:')
            ->phase('present-2', '  Title: {title}')
            ->phase('present-3', '  Priority: {priority}')
            ->phase('present-4', '  Estimate: {estimate} hours')
            ->phase('present-5', '  Tags: {tags}')
            ->phase('present-6', '  Content: {content preview}')
            ->phase('present-7', '  Prior Work: Memory IDs from ' . Store::get('PRIOR_WORK'))
            ->phase('warning', Operator::if(
                'estimate > 8 hours',
                'WARN: Estimate exceeds 8h. Strongly recommend running /task:decompose {task_id} after creation.'
            ))
            ->phase('prompt', 'Ask: "Create this task? (yes/no/modify)"')
            ->phase('gate', Operator::validate(
                'User response is YES, APPROVE, CONFIRM, or Y',
                'Wait for explicit approval. Allow modifications if requested.'
            ));

        // Workflow Step 6 - Create Task
        $this->guideline('workflow-step6')
            ->text('STEP 6 - Create Task After Approval')
            ->example()
            ->phase('create', VectorTaskMcp::call('task_create', '{
                    title: "' . Store::get('TASK_SPEC') . '.title",
                    content: "' . Store::get('TASK_SPEC') . '.content",
                    priority: "' . Store::get('TASK_SPEC') . '.priority",
                    tags: ' . Store::get('TASK_SPEC') . '.tags
                }'))
            ->phase('capture', Store::as('CREATED_TASK_ID', 'task ID from response'));

        // Workflow Step 7 - Post-Creation Actions
        $this->guideline('workflow-step7')
            ->text('STEP 7 - Post-Creation Summary and Recommendations')
            ->example()
            ->phase('confirm', 'Report: Task created with ID: ' . Store::get('CREATED_TASK_ID'))
            ->phase('decompose-check', Operator::if(
                'estimate > 8 hours',
                'STRONGLY RECOMMEND: Run /task:decompose ' . Store::get('CREATED_TASK_ID') . ' to break down this large task'
            ))
            ->phase('next-steps', 'Suggest: /task:next to start working, /task:list to view all tasks');

        // Workflow Step 8 - Store Insight
        $this->guideline('workflow-step8')
            ->text('STEP 8 - Store Task Creation Insight')
            ->example()
            ->phase('store', VectorMemoryMcp::call('store_memory', '{
                    content: "Created task: {title}. Domain: {domain}. Approach: {key insights from analysis}. Estimate: {hours}h.",
                    category: "tool-usage",
                    tags: ["task-creation", "{domain}"]
                }'));

        // Task Specification Format
        $this->guideline('task-format')
            ->text('Required task specification structure')
            ->example('Concise, action-oriented (max 10 words)')->key('title')
            ->example('Detailed with: objective, context, acceptance criteria, hints')->key('content')
            ->example('critical | high | medium | low')->key('priority')
            ->example('[category, domain, stack-tags]')->key('tags')
            ->example('1-8 hours (>8h needs decomposition)')->key('estimate');

        // Estimation Guidelines
        $this->guideline('estimation-rules')
            ->text('Task estimation guidelines')
            ->example('1-2h: Config changes, simple edits, minor fixes')->key('xs')
            ->example('2-4h: Small features, multi-file changes, tests')->key('s')
            ->example('4-6h: Moderate features, refactoring, integrations')->key('m')
            ->example('6-8h: Complex features, architectural changes')->key('l')
            ->example('>8h: MUST recommend /task:decompose')->key('xl');

        // Priority Assignment
        $this->guideline('priority-rules')
            ->text('Priority assignment criteria')
            ->example('Blockers, security issues, data integrity, production bugs')->key('critical')
            ->example('Key features, deadlines, dependencies for other work')->key('high')
            ->example('Standard features, improvements, optimizations')->key('medium')
            ->example('Nice-to-have, cosmetic, documentation, cleanup')->key('low');

        // Quality Gates
        $this->guideline('quality-gates')
            ->text('Validation checkpoints before task creation')
            ->example('$ARGUMENTS fully parsed and understood')
            ->example('Vector memory searched for prior work')
            ->example('Context gathered (code/docs if relevant)')
            ->example('Sequential thinking analysis completed')
            ->example('Task has: title, content, priority, tags, estimate')
            ->example('User approval explicitly received')
            ->example('Decomposition recommended if >8h');
    }
}
