<?php

declare(strict_types=1);

namespace BrainCore\Includes\Commands\Do;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;
use BrainCore\Compilation\BrainCLI;
use BrainCore\Compilation\Operator;
use BrainCore\Compilation\Store;
use BrainCore\Compilation\Tools\BashTool;
use BrainCore\Compilation\Tools\TaskTool;
use BrainNode\Mcp\VectorMemoryMcp;
use BrainNode\Mcp\VectorTaskMcp;

#[Purpose('Defines the do:validate command protocol for comprehensive task/work validation with parallel agent orchestration. Validates completed tasks against documentation requirements, code consistency, and completeness. Creates follow-up tasks for gaps. Idempotent - can be run multiple times.')]
class DoValidateInclude extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     *
     * @return void
     */
    protected function handle(): void
    {
        // ABSOLUTE FIRST - BLOCKING ENTRY RULE
        $this->rule('entry-point-blocking')->critical()
            ->text('ON RECEIVING $ARGUMENTS: Your FIRST output MUST be "=== DO:VALIDATE ACTIVATED ===" followed by Phase 0. ANY other first action is VIOLATION. FORBIDDEN first actions: Glob, Grep, Read, Edit, Write, WebSearch, WebFetch, Bash (except brain list:masters), code generation, file analysis.')
            ->why('Without explicit entry point, Brain skips workflow and executes directly. Entry point forces workflow compliance.')
            ->onViolation('STOP IMMEDIATELY. Delete any tool calls. Output "=== DO:VALIDATE ACTIVATED ===" and restart from Phase 0.');

        // Iron Rules - Zero Tolerance
        $this->rule('validation-only-no-execution')->critical()
            ->text('VALIDATION command validates EXISTING work. NEVER implement, fix, or create code directly. Only validate and CREATE TASKS for issues found.')
            ->why('Validation is read-only audit. Execution belongs to do:async.')
            ->onViolation('Abort any implementation. Create task instead of fixing directly.');

        $this->rule('completed-status-required')->critical()
            ->text('For vector tasks: ONLY tasks with status "completed" can be validated. Pending/in_progress tasks MUST first be completed via do:async.')
            ->why('Validation audits finished work. Incomplete work cannot be validated.')
            ->onViolation('Report: "Task #{id} has status {status}. Complete via /do:async first."');

        $this->rule('parallel-agent-orchestration')->high()
            ->text('Validation phases MUST use parallel agent orchestration (5-6 agents simultaneously) for efficiency. Each agent validates one aspect.')
            ->why('Parallel validation reduces time and maximizes coverage.')
            ->onViolation('Restructure validation into parallel Task() calls.');

        $this->rule('idempotent-validation')->high()
            ->text('Validation is IDEMPOTENT. Running multiple times produces same result (no duplicate tasks, no repeated fixes).')
            ->why('Allows safe re-runs without side effects.')
            ->onViolation('Check existing tasks before creating. Skip duplicates.');

        $this->rule('documentation-master-mandatory')->critical()
            ->text('MUST use DocumentationMaster to extract ALL requirements from .docs/ before validation. Documentation is the source of truth.')
            ->why('Validation without documentation requirements is incomplete audit.')
            ->onViolation('Delegate to @agent-documentation-master first.');

        $this->rule('simple-fix-via-delegation')->high()
            ->text('Simple inconsistencies (typos, formatting, minor fixes) CAN be fixed via agent delegation during validation. Complex issues MUST become tasks.')
            ->why('Balances efficiency (quick fixes) with traceability (complex issues tracked).')
            ->onViolation('Evaluate complexity: <15min fix = delegate, >15min = create task.');

        $this->rule('vector-memory-mandatory')->high()
            ->text('ALL validation results MUST be stored to vector memory. Search memory BEFORE creating duplicate tasks.')
            ->why('Memory prevents duplicate work and provides audit trail.')
            ->onViolation('Store validation summary with findings, fixes, and created tasks.');

        // Phase -1: Vector Task Reference Detection
        $this->guideline('phase-minus1-task-detection')
            ->goal('Detect if $ARGUMENTS is a vector task reference and fetch task details')
            ->example()
            ->phase('Parse $ARGUMENTS for task reference patterns: "task N", "task:N", "task #N", "task-N", "#N"')
            ->phase(Operator::if('$ARGUMENTS matches task reference pattern', [
                'Extract task_id from pattern',
                Store::as('IS_VECTOR_TASK', 'true'),
                Store::as('VECTOR_TASK_ID', '{extracted_id}'),
                VectorTaskMcp::call('task_get', '{task_id: $VECTOR_TASK_ID}'),
                Store::as('VECTOR_TASK', '{task object with title, content, status, parent_id, priority, tags}'),
                Operator::if('$VECTOR_TASK.status !== "completed"', [
                    Operator::output([
                        '=== VALIDATION BLOCKED ===',
                        'Task #{$VECTOR_TASK_ID} has status: {$VECTOR_TASK.status}',
                        'Only COMPLETED tasks can be validated.',
                        'Run /do:async task {$VECTOR_TASK_ID} to complete first.',
                    ]),
                    'ABORT validation',
                ]),
                Operator::if('$VECTOR_TASK.parent_id !== null', [
                    VectorTaskMcp::call('task_get', '{task_id: $VECTOR_TASK.parent_id}'),
                    Store::as('PARENT_TASK', '{parent task for context}'),
                ]),
                VectorTaskMcp::call('task_list', '{parent_id: $VECTOR_TASK_ID, limit: 50}'),
                Store::as('SUBTASKS', '{list of subtasks}'),
                Store::as('TASK_DESCRIPTION', '$VECTOR_TASK.title + $VECTOR_TASK.content'),
                Operator::output([
                    '=== VECTOR TASK LOADED ===',
                    'Task #{$VECTOR_TASK_ID}: {$VECTOR_TASK.title}',
                    'Status: {$VECTOR_TASK.status} | Priority: {$VECTOR_TASK.priority}',
                    'Parent: {$PARENT_TASK.title or "none"}',
                    'Subtasks: {$SUBTASKS.count}',
                ]),
            ]))
            ->phase(Operator::if('$ARGUMENTS is plain description', [
                Store::as('IS_VECTOR_TASK', 'false'),
                Store::as('TASK_DESCRIPTION', '$ARGUMENTS'),
            ]));

        // Phase 0: Agent Discovery and Validation Scope Preview
        $this->guideline('phase0-context-preview')
            ->goal('Discover available agents and present validation scope for approval')
            ->example()
            ->phase(Operator::output([
                '=== PHASE 0: VALIDATION PREVIEW ===',
            ]))
            ->phase(BashTool::describe(BrainCLI::LIST_MASTERS, 'Get available agents with capabilities'))
            ->phase(Store::as('AVAILABLE_AGENTS', '{agent_id: description mapping}'))
            ->phase(BashTool::describe(BrainCLI::DOCS('{keywords from $TASK_DESCRIPTION}'), 'Get documentation INDEX preview'))
            ->phase(Store::as('DOCS_PREVIEW', 'Documentation files available'))
            ->phase(Operator::output([
                'Task: {$TASK_DESCRIPTION}',
                'Available agents: {$AVAILABLE_AGENTS.count}',
                'Documentation files: {$DOCS_PREVIEW.count}',
                '',
                'Validation will delegate to agents:',
                '1. VectorMaster - deep memory research for context',
                '2. DocumentationMaster - requirements extraction',
                '3. Selected agents - parallel validation (5 aspects)',
                '',
                '⚠️  APPROVAL REQUIRED',
                '✅ approved/yes - start validation | ❌ no/modifications',
            ]))
            ->phase('WAIT for user approval')
            ->phase(Operator::verify('User approved'))
            ->phase(Operator::if('rejected', 'Accept modifications → Re-present → WAIT'))
            ->phase('IMMEDIATELY after approval - set task in_progress (validation IS execution)')
            ->phase(Operator::if('$IS_VECTOR_TASK === true', [
                VectorTaskMcp::call('task_update', '{task_id: $VECTOR_TASK_ID, status: "in_progress", comment: "Validation started after approval", append_comment: true}'),
                Operator::output(['📋 Vector task #{$VECTOR_TASK_ID} started (validation phase)']),
            ]));

        // Phase 1: Deep Context Gathering via VectorMaster Agent
        $this->guideline('phase1-context-gathering')
            ->goal('Delegate deep memory research to VectorMaster agent')
            ->example()
            ->phase(Operator::output([
                '',
                '=== PHASE 1: DEEP CONTEXT GATHERING ===',
                'Delegating to VectorMaster for deep memory research...',
            ]))
            ->phase('SELECT vector-master from $AVAILABLE_AGENTS')
            ->phase(Store::as('CONTEXT_AGENT', '{vector-master agent_id}'))
            ->phase(TaskTool::agent('{$CONTEXT_AGENT}', 'DEEP MEMORY RESEARCH for validation of "{$TASK_DESCRIPTION}": 1) Multi-probe search: implementation patterns, requirements, architecture decisions, past validations, bug fixes 2) Search across categories: code-solution, architecture, learning, bug-fix 3) Extract actionable insights for validation 4) Return: {implementations: [...], requirements: [...], patterns: [...], past_validations: [...], key_insights: [...]}. Store consolidated context.'))
            ->phase(Store::as('MEMORY_CONTEXT', '{VectorMaster agent results}'))
            ->phase(VectorTaskMcp::call('task_list', '{query: "{$TASK_DESCRIPTION}", limit: 10}'))
            ->phase(Store::as('RELATED_TASKS', 'Related vector tasks'))
            ->phase(Operator::output([
                'Context gathered via {$CONTEXT_AGENT}:',
                '- Memory insights: {$MEMORY_CONTEXT.key_insights.count}',
                '- Related tasks: {$RELATED_TASKS.count}',
            ]));

        // Phase 2: Documentation Requirements Extraction
        $this->guideline('phase1-documentation-extraction')
            ->goal('Extract ALL requirements from .docs/ via DocumentationMaster')
            ->example()
            ->phase(Operator::output([
                '',
                '=== PHASE 1: DOCUMENTATION REQUIREMENTS ===',
            ]))
            ->phase(BashTool::describe(BrainCLI::DOCS('{keywords from $TASK_DESCRIPTION}'), 'Get documentation INDEX'))
            ->phase(Store::as('DOCS_INDEX', 'Documentation file paths'))
            ->phase(Operator::if('$DOCS_INDEX not empty', [
                TaskTool::agent('documentation-master', 'Extract ALL requirements, acceptance criteria, constraints, and specifications from documentation files: {$DOCS_INDEX paths}. Return structured list: [{requirement_id, description, acceptance_criteria, related_files, priority}]. Store to vector memory.'),
                Store::as('DOCUMENTATION_REQUIREMENTS', '{structured requirements list}'),
            ]))
            ->phase(Operator::if('$DOCS_INDEX empty', [
                Store::as('DOCUMENTATION_REQUIREMENTS', '[]'),
                Operator::output(['WARNING: No documentation found. Validation will be limited.']),
            ]))
            ->phase(Operator::output([
                'Requirements extracted: {$DOCUMENTATION_REQUIREMENTS.count}',
                '{requirements summary}',
            ]));

        // Phase 2: Dynamic Agent Selection and Parallel Validation
        $this->guideline('phase2-parallel-validation')
            ->goal('Select best agents from $AVAILABLE_AGENTS and launch parallel validation')
            ->example()
            ->phase(Operator::output([
                '',
                '=== PHASE 2: PARALLEL VALIDATION ===',
            ]))
            ->phase('AGENT SELECTION: Analyze $AVAILABLE_AGENTS descriptions and select BEST agent for each validation aspect:')
            ->phase(Operator::do([
                'ASPECT 1 - COMPLETENESS: Select agent best suited for requirements verification (vector-master for memory research, explore for codebase)',
                'ASPECT 2 - CODE CONSISTENCY: Select agent for code pattern analysis (explore for codebase scanning)',
                'ASPECT 3 - TEST COVERAGE: Select agent for test analysis (explore for test file discovery)',
                'ASPECT 4 - DOCUMENTATION SYNC: Select agent for documentation analysis (documentation-master if docs-focused, explore otherwise)',
                'ASPECT 5 - DEPENDENCIES: Select agent for dependency analysis (explore for import scanning)',
            ]))
            ->phase(Store::as('SELECTED_AGENTS', '{aspect: agent_id mapping based on $AVAILABLE_AGENTS}'))
            ->phase(Operator::output([
                'Selected agents for validation:',
                '{$SELECTED_AGENTS mapping}',
                '',
                'Launching validation agents in parallel...',
            ]))
            ->phase('PARALLEL BATCH: Launch selected agents simultaneously with DEEP RESEARCH tasks')
            ->phase(Operator::do([
                TaskTool::agent('{$SELECTED_AGENTS.completeness}', 'DEEP RESEARCH - COMPLETENESS: For "{$TASK_DESCRIPTION}": 1) Search vector memory for past implementations and requirements 2) Scan codebase for implementation evidence 3) Map each requirement from {$DOCUMENTATION_REQUIREMENTS} to code 4) Return: [{requirement_id, status: implemented|partial|missing, evidence: file:line, memory_refs: [...]}]. Store findings.'),
                TaskTool::agent('{$SELECTED_AGENTS.consistency}', 'DEEP RESEARCH - CODE CONSISTENCY: For "{$TASK_DESCRIPTION}": 1) Search memory for project coding standards 2) Scan related files for pattern violations 3) Check naming, architecture, style consistency 4) Return: [{file, issue_type, severity, description, suggestion}]. Store findings.'),
                TaskTool::agent('{$SELECTED_AGENTS.tests}', 'DEEP RESEARCH - TEST COVERAGE: For "{$TASK_DESCRIPTION}": 1) Search memory for test patterns 2) Discover all related test files 3) Analyze coverage gaps 4) Run tests if possible 5) Return: [{test_file, coverage_status, missing_scenarios}]. Store findings.'),
                TaskTool::agent('{$SELECTED_AGENTS.docs}', 'DEEP RESEARCH - DOCUMENTATION SYNC: For "{$TASK_DESCRIPTION}": 1) Search memory for documentation standards 2) Compare code vs documentation 3) Check docblocks, README, API docs 4) Return: [{doc_type, sync_status, gaps}]. Store findings.'),
                TaskTool::agent('{$SELECTED_AGENTS.deps}', 'DEEP RESEARCH - DEPENDENCIES: For "{$TASK_DESCRIPTION}": 1) Search memory for dependency issues 2) Scan imports and dependencies 3) Check for broken/unused/circular refs 4) Return: [{file, dependency_issue, severity}]. Store findings.'),
            ]))
            ->phase(Store::as('VALIDATION_BATCH_1', '{results from all agents}'))
            ->phase(Operator::output([
                'Batch complete: {$SELECTED_AGENTS.count} validation checks finished',
            ]));

        // Phase 3: Results Aggregation and Analysis
        $this->guideline('phase3-results-aggregation')
            ->goal('Aggregate all validation results and categorize issues')
            ->example()
            ->phase(Operator::output([
                '',
                '=== PHASE 3: RESULTS AGGREGATION ===',
            ]))
            ->phase('Merge results from all validation agents')
            ->phase(Store::as('ALL_ISSUES', '{merged issues from all agents}'))
            ->phase('Categorize issues:')
            ->phase(Store::as('CRITICAL_ISSUES', '{issues with severity: critical}'))
            ->phase(Store::as('MAJOR_ISSUES', '{issues with severity: major}'))
            ->phase(Store::as('MINOR_ISSUES', '{issues with severity: minor}'))
            ->phase(Store::as('MISSING_REQUIREMENTS', '{requirements not implemented}'))
            ->phase(Operator::output([
                'Validation results:',
                '- Critical issues: {$CRITICAL_ISSUES.count}',
                '- Major issues: {$MAJOR_ISSUES.count}',
                '- Minor issues: {$MINOR_ISSUES.count}',
                '- Missing requirements: {$MISSING_REQUIREMENTS.count}',
            ]));

        // Phase 4: Quick Fixes via Dynamic Agent Delegation
        $this->guideline('phase4-quick-fixes')
            ->goal('Fix minor issues via dynamically selected agents from $AVAILABLE_AGENTS')
            ->example()
            ->phase(Operator::output([
                '',
                '=== PHASE 4: QUICK FIXES ===',
            ]))
            ->phase(Operator::if('$MINOR_ISSUES not empty', [
                'Filter fixable issues: single-file, <15min estimate, no architecture impact',
                Store::as('FIXABLE_ISSUES', '{filtered minor issues}'),
                Operator::forEach('issue in $FIXABLE_ISSUES', [
                    'SELECT BEST AGENT from $AVAILABLE_AGENTS based on issue type:',
                    '- Code style issues → agent with code formatting expertise',
                    '- Documentation issues → documentation-master',
                    '- Test issues → agent with test expertise',
                    '- Other → explore for general fixes',
                    Store::as('FIX_AGENT', '{selected agent_id}'),
                    Operator::output(['Fixing via {$FIX_AGENT}: {issue.description} in {issue.file}']),
                    TaskTool::agent('{$FIX_AGENT}', 'FIX ISSUE: {issue.description} in {issue.file}. 1) Search memory for similar fixes 2) Apply fix 3) Verify fix works 4) Store result to vector memory.'),
                    Store::as('FIX_RESULTS[{issue.id}]', 'Fixed'),
                ]),
                Operator::output(['Quick fixes applied: {$FIXABLE_ISSUES.count}']),
            ]))
            ->phase(Operator::if('$MINOR_ISSUES empty', [
                Operator::output(['No minor issues to fix']),
            ]));

        // Phase 5: Task Creation for Complex Issues
        $this->guideline('phase5-task-creation')
            ->goal('Create tasks for complex issues and missing requirements')
            ->example()
            ->phase(Operator::output([
                '',
                '=== PHASE 5: TASK CREATION ===',
            ]))
            ->phase('Check existing tasks to avoid duplicates')
            ->phase(VectorTaskMcp::call('task_list', '{query: "fix issues {$TASK_DESCRIPTION}", limit: 20}'))
            ->phase(Store::as('EXISTING_FIX_TASKS', 'Existing fix tasks'))
            ->phase(Operator::forEach('issue in $CRITICAL_ISSUES + $MAJOR_ISSUES', [
                Operator::if('NOT exists in $EXISTING_FIX_TASKS', [
                    VectorTaskMcp::call('task_create', '{title: "Fix: {issue.description}", content: "Issue found during validation of {$TASK_DESCRIPTION}.\\n\\nDetails:\\n- File: {issue.file}\\n- Type: {issue.type}\\n- Severity: {issue.severity}\\n\\nSuggestion: {issue.suggestion}", priority: "{issue.severity === critical ? high : medium}", tags: ["validation-fix", "{issue.type}"], parent_id: $VECTOR_TASK_ID or null}'),
                    Store::as('CREATED_TASKS[]', '{task_id}'),
                    Operator::output(['Created task for: {issue.description}']),
                ]),
            ]))
            ->phase(Operator::forEach('req in $MISSING_REQUIREMENTS', [
                Operator::if('NOT exists in $EXISTING_FIX_TASKS', [
                    VectorTaskMcp::call('task_create', '{title: "Implement: {req.description}", content: "Missing requirement found during validation.\\n\\nRequirement: {req.description}\\nAcceptance criteria: {req.acceptance_criteria}\\nRelated files: {req.related_files}", priority: "{req.priority}", tags: ["validation-missing", "requirement"], parent_id: $VECTOR_TASK_ID or null}'),
                    Store::as('CREATED_TASKS[]', '{task_id}'),
                    Operator::output(['Created task for missing requirement: {req.description}']),
                ]),
            ]))
            ->phase(Operator::output([
                'Tasks created: {$CREATED_TASKS.count}',
            ]));

        // Phase 6: Validation Completion
        $this->guideline('phase6-completion')
            ->goal('Complete validation, update task status, store summary to memory')
            ->example()
            ->phase(Operator::output([
                '',
                '=== PHASE 6: VALIDATION COMPLETE ===',
            ]))
            ->phase(Store::as('VALIDATION_SUMMARY', '{all_issues_count, fixed_count, tasks_created_count, pass_rate}'))
            ->phase(Store::as('VALIDATION_STATUS', Operator::if('$CRITICAL_ISSUES.count === 0 AND $MISSING_REQUIREMENTS.count === 0', 'PASSED', 'NEEDS_WORK')))
            ->phase(VectorMemoryMcp::call('store_memory', '{content: "Validation of {$TASK_DESCRIPTION}\\n\\nStatus: {$VALIDATION_STATUS}\\nCritical: {$CRITICAL_ISSUES.count}\\nMajor: {$MAJOR_ISSUES.count}\\nMinor fixed: {$FIXABLE_ISSUES.count}\\nTasks created: {$CREATED_TASKS.count}\\n\\nFindings:\\n{summary of key findings}", category: "code-solution", tags: ["validation", "audit"]}'))
            ->phase(Operator::if('$IS_VECTOR_TASK === true', [
                Operator::if('$VALIDATION_STATUS === "PASSED"', [
                    VectorTaskMcp::call('task_update', '{task_id: $VECTOR_TASK_ID, status: "completed", comment: "Validation PASSED. All requirements implemented, no critical issues.", append_comment: true}'),
                ]),
                Operator::if('$VALIDATION_STATUS === "NEEDS_WORK"', [
                    VectorTaskMcp::call('task_update', '{task_id: $VECTOR_TASK_ID, status: "completed", comment: "Validation completed with findings. Created {$CREATED_TASKS.count} follow-up tasks. Critical: {$CRITICAL_ISSUES.count}, Major: {$MAJOR_ISSUES.count}, Missing: {$MISSING_REQUIREMENTS.count}", append_comment: true}'),
                ]),
            ]))
            ->phase(Operator::output([
                '',
                '=== VALIDATION REPORT ===',
                'Task: {$TASK_DESCRIPTION}',
                'Status: {$VALIDATION_STATUS}',
                '',
                '| Metric | Count |',
                '|--------|-------|',
                '| Critical issues | {$CRITICAL_ISSUES.count} |',
                '| Major issues | {$MAJOR_ISSUES.count} |',
                '| Minor issues fixed | {$FIXABLE_ISSUES.count} |',
                '| Tasks created | {$CREATED_TASKS.count} |',
                '| Missing requirements | {$MISSING_REQUIREMENTS.count} |',
                '',
                '{IF $CREATED_TASKS.count > 0: "Follow-up tasks: {$CREATED_TASKS}"}',
                '',
                'Validation stored to vector memory.',
            ]));

        // Error Handling
        $this->guideline('error-handling')
            ->text('Graceful error handling for validation process')
            ->example()
            ->phase()->if('vector task not found', [
                'Report: "Vector task #{id} not found"',
                'Suggest: Check task ID with ' . VectorTaskMcp::method('task_list'),
                'Abort validation',
            ])
            ->phase()->if('vector task not completed', [
                'Report: "Vector task #{id} status is {status}, not completed"',
                'Suggest: Run /do:async task #{id} first',
                'Abort validation',
            ])
            ->phase()->if('no documentation found', [
                'Warn: "No documentation in .docs/ for this task"',
                'Continue with limited validation (code-only checks)',
            ])
            ->phase()->if('agent validation fails', [
                'Log: "Validation agent {N} failed: {error}"',
                'Continue with remaining agents',
                'Report partial validation in summary',
            ])
            ->phase()->if('task creation fails', [
                'Log: "Failed to create task: {error}"',
                'Store issue details to vector memory for manual review',
                'Continue with remaining tasks',
            ]);

        // Constraints and Validation
        $this->guideline('constraints')
            ->text('Validation constraints and limits')
            ->example()
            ->phase('Max 6 parallel validation agents per batch')
            ->phase('Max 10 quick fixes per validation run')
            ->phase('Max 20 tasks created per validation run')
            ->phase('Validation timeout: 5 minutes per agent')
            ->phase(Operator::verify([
                'completed_status_enforced = true (for vector tasks)',
                'parallel_agents_used = true',
                'documentation_checked = true',
                'results_stored_to_memory = true',
            ]));

        // Examples
        $this->guideline('example-vector-task')
            ->scenario('Validate completed vector task')
            ->example()
            ->phase('input', '"task 15" or "validate task:15"')
            ->phase('detection', 'Task #15 loaded, status: completed')
            ->phase('flow', 'Task Detection → Context → Docs → Parallel Validation (5 agents) → Aggregate → Quick Fixes → Create Tasks → Complete')
            ->phase('result', 'Validation PASSED/NEEDS_WORK, N tasks created');

        $this->guideline('example-plain-request')
            ->scenario('Validate work by description')
            ->example()
            ->phase('input', '"validate user authentication implementation"')
            ->phase('flow', 'Context from memory → Docs requirements → Parallel Validation → Aggregate → Quick Fixes → Create Tasks → Report')
            ->phase('result', 'Validation report with findings and created tasks');

        $this->guideline('example-rerun')
            ->scenario('Re-run validation (idempotent)')
            ->example()
            ->phase('input', '"task 15" (already validated before)')
            ->phase('behavior', 'Skips existing tasks, only creates NEW issues found')
            ->phase('result', 'Same/updated validation report, no duplicate tasks');

        // Response Format
        $this->guideline('response-format')
            ->text('=== headers | Parallel: agent batch indicators | Tables: validation results | No filler | Created tasks listed');
    }
}