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
use BrainNode\Mcp\VectorMemoryMcp;
use BrainNode\Mcp\VectorTaskMcp;

#[Purpose('Project task initializer that performs exhaustive research via specialized agents, analyzes all project materials, and creates the foundational first layer of root tasks for subsequent decomposition. NEVER executes tasks - only creates them.')]
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
            ->text('Project task initializer that orchestrates comprehensive research via specialized agents, synthesizes findings into strategic root tasks, and creates the foundational task layer for project execution. This is the FIRST and MOST CRITICAL step in project planning.');

        // Iron Rules
        $this->rule('delegate-research-to-agents')->critical()
            ->text('MUST delegate ALL research to specialized agents. Brain cannot comprehensively scan entire project alone.')
            ->why('Agents have specialized capabilities for deep exploration. Direct scanning misses critical details.')
            ->onViolation('Use Task(@agent-explore) for codebase, WebResearchMaster for external context, read docs via agents.');

        $this->rule('exhaustive-research-mandatory')->critical()
            ->text('MUST complete ALL research phases before task generation: documentation, codebase, vector memory, external context.')
            ->why('First layer tasks define entire project direction. Incomplete research leads to missed requirements and rework.')
            ->onViolation('STOP. Complete all research steps. Do not skip any phase.');

        $this->rule('create-only-no-execution')->critical()
            ->text('This command ONLY creates root tasks. NEVER execute any task after creation, regardless of simplicity.')
            ->why('Init-task creates strategic foundation. Execution is separate concern via /task:next or /do commands.')
            ->onViolation('STOP immediately after task creation. Return control to user.');

        $this->rule('mandatory-user-approval')->critical()
            ->text('MUST get explicit user approval before creating ANY tasks')
            ->why('User must validate task breakdown before committing to vector storage')
            ->onViolation('Present task list and wait for user confirmation');

        $this->rule('estimate-required')->critical()
            ->text('MUST provide time estimate for EACH task')
            ->why('Estimates enable planning and identify tasks needing decomposition')
            ->onViolation('Add estimate before presenting task');

        $this->rule('root-tasks-are-epics')->high()
            ->text('Root tasks should be EPICS (major work streams). Individual tasks created later via /task:create or /task:decompose.')
            ->why('First layer defines strategic direction, not tactical implementation details.')
            ->onViolation('Consolidate granular tasks into broader epics. Target 5-15 root tasks for typical project.');

        $this->rule('max-task-estimate')->high()
            ->text('Each root task estimate should be 8-40 hours (will be decomposed)')
            ->why('Root tasks are epics that will be broken down. Too small = too granular for first layer.')
            ->onViolation('Merge small tasks into larger epics or flag for /task:decompose');

        $this->rule('no-creation-without-confirmation')->critical()
            ->text('NO task creation without explicit user YES/APPROVE/CONFIRM')
            ->why('Prevents accidental task creation and allows user revision')
            ->onViolation('Wait for explicit approval signal');

        // ============================================
        // PHASE 1: PRE-FLIGHT CHECKS
        // ============================================

        // Workflow Step 0 - Check Existing State
        $this->guideline('workflow-step0')
            ->text('STEP 0 - Pre-flight: Check Existing Tasks and Project State')
            ->example()
            ->phase('action-1', VectorTaskMcp::call('task_stats', '{}') . ' → check if tasks already exist')
            ->phase('action-2', VectorTaskMcp::call('task_list', '{status: "pending", limit: 20}') . ' → list existing pending tasks')
            ->phase('decision', Operator::if(
                'existing tasks > 0',
                'STOP. Show existing tasks. Ask user: "Tasks exist. Options: (1) Continue and add more, (2) Clear all and restart, (3) Abort"',
                'Proceed with initialization'
            ))
            ->phase('output', Store::as('EXISTING_STATE', 'task count, existing task IDs if any'));

        // ============================================
        // PHASE 2: EXHAUSTIVE RESEARCH (via Agents)
        // ============================================

        // Workflow Step 1 - Documentation Research (Agent-Delegated)
        $this->guideline('workflow-step1')
            ->text('STEP 1 - Documentation Research via Explore Agent (MANDATORY)')
            ->example()
            ->phase('action-1', TaskTool::agent('explore', 'DOCUMENTATION SCAN: Find and analyze ALL documentation in project. Search: README*, CHANGELOG*, docs/, .docs/, *.md files, API specs, architecture docs. Return: comprehensive summary of project purpose, features, requirements, constraints.'))
            ->phase('action-2', BashTool::call(BrainCLI::DOCS) . ' → ' . Store::as('DOCS_INDEX', 'indexed documentation paths'))
            ->phase('action-3', ReadTool::call('README.md') . ' → ' . Store::as('README', 'project overview'))
            ->phase('output', Store::as('DOCUMENTATION', 'complete documentation analysis from Explore agent'));

        // Workflow Step 2 - Codebase Deep Exploration (Agent-Delegated)
        $this->guideline('workflow-step2')
            ->text('STEP 2 - Codebase Deep Exploration via Explore Agent (MANDATORY)')
            ->example()
            ->phase('action-1', TaskTool::agent('explore', 'ARCHITECTURE ANALYSIS: Comprehensive codebase scan. Analyze: directory structure, entry points, core modules, design patterns, tech stack, dependencies (composer.json/package.json), database schema, API routes, test coverage. Return: detailed architecture map with components, relationships, and technical debt areas.'))
            ->phase('output', Store::as('CODEBASE_ARCHITECTURE', 'complete architecture analysis'));

        // Workflow Step 3 - Vector Memory Research
        $this->guideline('workflow-step3')
            ->text('STEP 3 - Vector Memory Research for Prior Context (MANDATORY)')
            ->example()
            ->phase('action-1', VectorMemoryMcp::call('search_memories', '{query: "project architecture implementation", limit: 10, category: "architecture"}'))
            ->phase('action-2', VectorMemoryMcp::call('search_memories', '{query: "project requirements features", limit: 10, category: "learning"}'))
            ->phase('action-3', VectorMemoryMcp::call('search_memories', '{query: "project bugs issues problems", limit: 5, category: "bug-fix"}'))
            ->phase('action-4', VectorMemoryMcp::call('search_memories', '{query: "project decisions trade-offs", limit: 5, category: "code-solution"}'))
            ->phase('analyze', 'Extract: past decisions, known issues, architectural insights, lessons learned')
            ->phase('output', Store::as('PRIOR_KNOWLEDGE', 'memory IDs, insights, warnings, recommendations'));

        // Workflow Step 4 - External Context (if needed)
        $this->guideline('workflow-step4')
            ->text('STEP 4 - External Context Research (if project uses external APIs/services)')
            ->example()
            ->phase('decision', Operator::if(
                'project integrates external services/APIs',
                TaskTool::agent('web-research-master', 'Research external dependencies: {services}. Find: API documentation, best practices, known issues, integration patterns.'),
                Operator::skip('No external dependencies requiring research')
            ))
            ->phase('output', Store::as('EXTERNAL_CONTEXT', 'external service documentation, integration notes'));

        // ============================================
        // PHASE 3: SYNTHESIS AND ANALYSIS
        // ============================================

        // Workflow Step 5 - Synthesize Project Context
        $this->guideline('workflow-step5')
            ->text('STEP 5 - Synthesize All Research into Project Context')
            ->example()
            ->phase('input-1', 'Combine: ' . Store::get('DOCUMENTATION'))
            ->phase('input-2', 'Combine: ' . Store::get('CODEBASE_ARCHITECTURE'))
            ->phase('input-3', 'Combine: ' . Store::get('PRIOR_KNOWLEDGE'))
            ->phase('input-4', 'Combine: ' . Store::get('EXTERNAL_CONTEXT'))
            ->phase('synthesize-1', 'Extract: project scope, primary objectives, success criteria')
            ->phase('synthesize-2', 'Identify: requirements (functional, non-functional), constraints, risks')
            ->phase('synthesize-3', 'Assess: current state (greenfield/existing/refactor), completion percentage')
            ->phase('synthesize-4', 'Map: major work streams, component boundaries, dependency graph')
            ->phase('output', Store::as('PROJECT_CONTEXT', 'comprehensive project understanding'));

        // Workflow Step 6 - Strategic Task Decomposition
        $this->guideline('workflow-step6')
            ->text('STEP 6 - Strategic Epic Decomposition via Sequential Thinking')
            ->example()
            ->phase('thinking', SequentialThinkingMcp::call('sequentialthinking', '{
                    thought: "Analyzing comprehensive project context for strategic epic decomposition. Context: ' . Store::get('PROJECT_CONTEXT') . '",
                    thoughtNumber: 1,
                    totalThoughts: 6,
                    nextThoughtNeeded: true
                }'))
            ->phase('decompose-1', 'Identify 5-15 major work streams (EPICS) from synthesized requirements')
            ->phase('decompose-2', 'Define each epic: scope, boundaries, deliverables, dependencies')
            ->phase('decompose-3', 'Estimate each epic (8-40 hours range for root tasks)')
            ->phase('decompose-4', 'Assign priority: critical (blockers) > high (core) > medium (features) > low (nice-to-have)')
            ->phase('decompose-5', 'Add strategic tags: [epic, {domain}, {stack}, {phase}]')
            ->phase('decompose-6', 'Identify inter-epic dependencies and optimal execution order')
            ->phase('output', Store::as('EPIC_LIST', 'array of {title, content, priority, estimate, tags, dependencies}'));

        // ============================================
        // PHASE 4: USER APPROVAL AND CREATION
        // ============================================

        // Workflow Step 7 - Present for Approval
        $this->guideline('workflow-step7')
            ->text('STEP 7 - Present Epic List for User Approval (MANDATORY GATE)')
            ->example()
            ->phase('format', 'Format epic list as table: # | Epic Title | Priority | Estimate | Dependencies | Tags')
            ->phase('summary-1', 'Total epics: {count}')
            ->phase('summary-2', 'Total estimated hours: {sum}')
            ->phase('summary-3', 'Critical path: epics with dependencies')
            ->phase('summary-4', 'Research sources: Documentation, Codebase, Memory ({memory_count} insights), External')
            ->phase('recommendations', 'After creation, run /task:decompose {epic_id} for each epic to create subtasks')
            ->phase('prompt', 'Ask: "Approve epic creation? (yes/no/modify)"')
            ->phase('gate', Operator::validate(
                'User response is YES, APPROVE, or CONFIRM',
                'Wait for explicit approval. Allow modifications if requested.'
            ));

        // Workflow Step 8 - Create Tasks
        $this->guideline('workflow-step8')
            ->text('STEP 8 - Create Root Tasks (Epics) After Approval')
            ->example()
            ->phase('create', VectorTaskMcp::call('task_create_bulk', '{tasks: ' . Store::get('EPIC_LIST') . '}'))
            ->phase('verify', VectorTaskMcp::call('task_stats', '{}') . ' → confirm creation')
            ->phase('capture', Store::as('CREATED_EPIC_IDS', 'array of created task IDs'));

        // Workflow Step 9 - Summary and Stop
        $this->guideline('workflow-step9')
            ->text('STEP 9 - Report Summary (END - NO EXECUTION)')
            ->example()
            ->phase('report-1', 'Created epics: ' . Store::get('CREATED_EPIC_IDS'))
            ->phase('report-2', 'Total estimated hours: {sum}')
            ->phase('report-3', 'NEXT STEPS:')
            ->phase('report-4', '  1. /task:decompose {epic_id} - Break down each epic into subtasks')
            ->phase('report-5', '  2. /task:list - View all tasks')
            ->phase('report-6', '  3. /task:next - Start working on first task')
            ->phase('stop', 'STOP HERE. Do NOT execute any task. Return control to user.');

        // Workflow Step 10 - Store Initialization Insight
        $this->guideline('workflow-step10')
            ->text('STEP 10 - Store Project Initialization Insight')
            ->example()
            ->phase('store', VectorMemoryMcp::call('store_memory', '{
                    content: "Project initialized with {epic_count} epics. Total estimate: {hours}h. Key areas: {domains}. Tech stack: {stack}. Critical path: {critical_epics}.",
                    category: "architecture",
                    tags: ["project-init", "epics", "planning"]
                }'));

        // ============================================
        // REFERENCE: FORMATS AND GUIDELINES
        // ============================================

        // Epic Format Specification
        $this->guideline('epic-format')
            ->text('Required epic (root task) structure')
            ->example('title: Concise epic name capturing major work stream (max 10 words)')->key('title')
            ->example('content: Detailed scope with: objectives, boundaries, deliverables, acceptance criteria, known dependencies, risk factors')->key('content')
            ->example('priority: critical | high | medium | low')->key('priority')
            ->example('tags: [epic, {domain}, {stack}, {phase}]')->key('tags')
            ->example('estimate: 8-40 hours (will be decomposed into subtasks)')->key('estimate');

        // Estimation Guidelines for Epics
        $this->guideline('estimation-rules')
            ->text('Epic estimation guidelines (larger than regular tasks)')
            ->example('8-16h: Focused epic, single domain, limited scope')->key('small')
            ->example('16-24h: Standard epic, cross-component, moderate complexity')->key('medium')
            ->example('24-32h: Large epic, architectural changes, multiple integrations')->key('large')
            ->example('32-40h: Major epic, foundational work, high complexity')->key('xlarge')
            ->example('>40h: Consider splitting into multiple epics')->key('split');

        // Priority Assignment
        $this->guideline('priority-assignment')
            ->text('Priority assignment criteria for epics')
            ->example('critical: Foundation work, blockers for other epics, security, data integrity')->key('critical')
            ->example('high: Core functionality, key features, dependencies for multiple tasks')->key('high')
            ->example('medium: Standard features, improvements, optimizations')->key('medium')
            ->example('low: Nice-to-have, polish, documentation, cleanup')->key('low');

        // Quality Gates
        $this->guideline('quality-gates')
            ->text('ALL quality gates MUST pass before epic creation')
            ->example('Step 0: Existing tasks checked, user informed')
            ->example('Step 1: Documentation fully scanned via Explore agent')
            ->example('Step 2: Codebase architecture analyzed via Explore agent')
            ->example('Step 3: Vector memory searched (4 categories)')
            ->example('Step 4: External context researched (if applicable)')
            ->example('Step 5: All research synthesized into project context')
            ->example('Step 6: Strategic decomposition completed via Sequential Thinking')
            ->example('Step 7: User approval explicitly received')
            ->example('Step 9: STOP after creation - do NOT execute');
    }
}
