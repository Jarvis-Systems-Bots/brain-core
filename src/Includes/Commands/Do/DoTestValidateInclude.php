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

#[Purpose('Defines the do:test-validate command protocol for comprehensive test validation with parallel agent orchestration. Validates test coverage against documentation requirements, test quality (no bloat, real workflows), test consistency, and completeness. Creates follow-up tasks for gaps. Idempotent - can be run multiple times.')]
class DoTestValidateInclude extends IncludeArchetype
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
            ->text('ON RECEIVING $ARGUMENTS: Your FIRST output MUST be "=== DO:TEST-VALIDATE ACTIVATED ===" followed by Phase 0. ANY other first action is VIOLATION. FORBIDDEN first actions: Glob, Grep, Read, Edit, Write, WebSearch, WebFetch, Bash (except brain list:masters), code generation, file analysis.')
            ->why('Without explicit entry point, Brain skips workflow and executes directly. Entry point forces workflow compliance.')
            ->onViolation('STOP IMMEDIATELY. Delete any tool calls. Output "=== DO:TEST-VALIDATE ACTIVATED ===" and restart from Phase 0.');

        // Iron Rules - Zero Tolerance
        $this->rule('test-validation-only')->critical()
            ->text('TEST VALIDATION command validates EXISTING tests. NEVER write tests directly. Only validate and CREATE TASKS for missing/broken tests.')
            ->why('Validation is read-only audit. Test writing belongs to do:async.')
            ->onViolation('Abort any test writing. Create task instead.');

        $this->rule('completed-status-required')->critical()
            ->text('For vector tasks: ONLY tasks with status "completed", "tested", or "validated" can be test-validated. Pending/in_progress/stopped tasks MUST first be completed via do:async.')
            ->why('Test validation audits finished work. Incomplete work cannot be validated.')
            ->onViolation('Report: "Task #{id} has status {status}. Complete via /do:async first."');

        $this->rule('output-status-tested-not-validated')->critical()
            ->text('This command ALWAYS sets status to "tested", NEVER "validated". Input task can have ANY status (completed/tested/validated) - all are valid for re-testing. Command name "test-validate" = ACTION of validating tests, NOT the status to set.')
            ->why('Status "validated" is set ONLY by /do:validate command. Tasks can cycle through tested↔validated multiple times. This command always outputs "tested" regardless of input status.')
            ->onViolation('Set status to "tested". Do NOT ask for confirmation based on current status - any status is valid input.');

        $this->rule('real-workflow-tests-only')->critical()
            ->text('Tests MUST cover REAL workflows end-to-end. Reject bloated tests that test implementation details instead of behavior. Quality over quantity.')
            ->why('Bloated tests are maintenance burden, break on refactoring, provide false confidence.')
            ->onViolation('Flag bloated tests for refactoring. Create task to simplify.');

        $this->rule('documentation-requirements-coverage')->critical()
            ->text('EVERY requirement in .docs/ MUST have corresponding test coverage. Missing coverage = immediate task creation.')
            ->why('Documentation defines expected behavior. Untested requirements are unverified.')
            ->onViolation('Create task for each uncovered requirement.');

        $this->rule('parallel-agent-orchestration')->high()
            ->text('Test validation phases MUST use parallel agent orchestration (5-6 agents simultaneously) for efficiency. Each agent validates one aspect.')
            ->why('Parallel validation reduces time and maximizes coverage.')
            ->onViolation('Restructure validation into parallel Task() calls.');

        $this->rule('idempotent-validation')->high()
            ->text('Test validation is IDEMPOTENT. Running multiple times produces same result (no duplicate tasks, no repeated analysis).')
            ->why('Allows safe re-runs without side effects.')
            ->onViolation('Check existing tasks before creating. Skip duplicates.');

        $this->rule('vector-memory-mandatory')->high()
            ->text('ALL test validation results MUST be stored to vector memory. Search memory BEFORE creating duplicate tasks.')
            ->why('Memory prevents duplicate work and provides audit trail.')
            ->onViolation('Store validation summary with findings and created tasks.');

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
                Operator::if('$VECTOR_TASK.status NOT IN (completed, tested, validated)', [
                    Operator::output([
                        '=== TEST VALIDATION BLOCKED ===',
                        'Task #{$VECTOR_TASK_ID} has status: {$VECTOR_TASK.status}',
                        'Only COMPLETED/TESTED/VALIDATED tasks can be test-validated.',
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
                Store::as('TASK_PARENT_ID', '$VECTOR_TASK_ID'),
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
                Store::as('TASK_PARENT_ID', 'null'),
            ]));

        // Phase 0: Agent Discovery and Test Validation Scope Preview
        $this->guideline('phase0-validation-preview')
            ->goal('Discover available agents and present test validation scope for approval')
            ->example()
            ->phase(Operator::output([
                '=== PHASE 0: TEST VALIDATION PREVIEW ===',
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
                'Test validation will delegate to agents:',
                '1. VectorMaster - deep memory research for test context',
                '2. DocumentationMaster - testable requirements extraction',
                '3. Selected agents - test discovery + parallel validation (6 aspects)',
                '',
                '⚠️  APPROVAL REQUIRED',
                '✅ approved/yes - start test validation | ❌ no/modifications',
            ]))
            ->phase('WAIT for user approval')
            ->phase(Operator::verify('User approved'))
            ->phase(Operator::if('rejected', 'Accept modifications → Re-present → WAIT'))
            ->phase('IMMEDIATELY after approval - set task in_progress (test validation IS execution)')
            ->phase(Operator::if('$IS_VECTOR_TASK === true', [
                VectorTaskMcp::call('task_update', '{task_id: $VECTOR_TASK_ID, status: "in_progress", comment: "Test validation started after approval", append_comment: true}'),
                Operator::output(['📋 Vector task #{$VECTOR_TASK_ID} started (test validation phase)']),
            ]));

        // Phase 1: Deep Test Context Gathering via VectorMaster Agent
        $this->guideline('phase1-context-gathering')
            ->goal('Delegate deep test context research to VectorMaster agent')
            ->example()
            ->phase(Operator::output([
                '',
                '=== PHASE 1: DEEP TEST CONTEXT ===',
                'Delegating to VectorMaster for deep memory research...',
            ]))
            ->phase('SELECT vector-master from $AVAILABLE_AGENTS')
            ->phase(Store::as('CONTEXT_AGENT', '{vector-master agent_id}'))
            ->phase(TaskTool::agent('{$CONTEXT_AGENT}', 'DEEP MEMORY RESEARCH for test validation of "{$TASK_DESCRIPTION}": 1) Multi-probe search: past test implementations, test patterns, testing best practices, test failures, coverage gaps 2) Search across categories: code-solution, learning, bug-fix 3) Extract test-specific insights: what worked, what failed, patterns used 4) Return: {test_history: [...], test_patterns: [...], past_failures: [...], quality_standards: [...], key_insights: [...]}. Store consolidated test context.'))
            ->phase(Store::as('TEST_MEMORY_CONTEXT', '{VectorMaster agent results}'))
            ->phase(VectorTaskMcp::call('task_list', '{query: "test {$TASK_DESCRIPTION}", limit: 10}'))
            ->phase(Store::as('RELATED_TEST_TASKS', 'Related test tasks'))
            ->phase(Operator::output([
                'Context gathered via {$CONTEXT_AGENT}:',
                '- Test insights: {$TEST_MEMORY_CONTEXT.key_insights.count}',
                '- Related test tasks: {$RELATED_TEST_TASKS.count}',
            ]));

        // Phase 2: Documentation Requirements Extraction
        $this->guideline('phase1-documentation-extraction')
            ->goal('Extract ALL testable requirements from .docs/ via DocumentationMaster')
            ->example()
            ->phase(Operator::output([
                '',
                '=== PHASE 1: DOCUMENTATION REQUIREMENTS ===',
            ]))
            ->phase(BashTool::describe(BrainCLI::DOCS('{keywords from $TASK_DESCRIPTION}'), 'Get documentation INDEX'))
            ->phase(Store::as('DOCS_INDEX', 'Documentation file paths'))
            ->phase(Operator::if('$DOCS_INDEX not empty', [
                TaskTool::agent('documentation-master', 'Extract ALL TESTABLE requirements from documentation files: {$DOCS_INDEX paths}. For each requirement identify: [{requirement_id, description, testable_scenarios: [...], acceptance_criteria, expected_test_type: unit|feature|integration|e2e, priority}]. Focus on BEHAVIOR not implementation. Store to vector memory.'),
                Store::as('DOCUMENTATION_REQUIREMENTS', '{structured testable requirements list}'),
            ]))
            ->phase(Operator::if('$DOCS_INDEX empty', [
                Store::as('DOCUMENTATION_REQUIREMENTS', '[]'),
                Operator::output(['WARNING: No documentation found. Test validation will be limited to existing tests only.']),
            ]))
            ->phase(Operator::output([
                'Testable requirements extracted: {$DOCUMENTATION_REQUIREMENTS.count}',
                '{requirements summary with test types}',
            ]));

        // Phase 2: Test Discovery via Dynamic Agent Selection
        $this->guideline('phase2-test-discovery')
            ->goal('Select best agent from $AVAILABLE_AGENTS and discover all existing tests')
            ->example()
            ->phase(Operator::output([
                '',
                '=== PHASE 2: TEST DISCOVERY ===',
            ]))
            ->phase('SELECT AGENT for test discovery from $AVAILABLE_AGENTS (prefer explore for codebase scanning)')
            ->phase(Store::as('DISCOVERY_AGENT', '{selected agent_id based on descriptions}'))
            ->phase(TaskTool::agent('{$DISCOVERY_AGENT}', 'DEEP RESEARCH - TEST DISCOVERY for "{$TASK_DESCRIPTION}": 1) Search vector memory for past test patterns and locations 2) Scan codebase for test directories (tests/, spec/, __tests__) 3) Find ALL related test files: unit, feature, integration, e2e 4) Analyze test structure and coverage 5) Return: [{test_file, test_type, test_classes, test_methods, related_source_files}]. Store findings to vector memory.'))
            ->phase(Store::as('DISCOVERED_TESTS', '{list of test files with metadata}'))
            ->phase(Operator::output([
                'Tests discovered via {$DISCOVERY_AGENT}: {$DISCOVERED_TESTS.count} files',
                '{test files summary by type}',
            ]));

        // Phase 3: Dynamic Agent Selection and Parallel Test Validation
        $this->guideline('phase3-parallel-validation')
            ->goal('Select best agents from $AVAILABLE_AGENTS and launch parallel test validation')
            ->example()
            ->phase(Operator::output([
                '',
                '=== PHASE 3: PARALLEL TEST VALIDATION ===',
            ]))
            ->phase('AGENT SELECTION: Analyze $AVAILABLE_AGENTS descriptions and select BEST agent for each test validation aspect:')
            ->phase(Operator::do([
                'ASPECT 1 - REQUIREMENTS COVERAGE: Select agent for requirements-to-test mapping (vector-master for memory, explore for codebase)',
                'ASPECT 2 - TEST QUALITY: Select agent for code quality analysis (explore for pattern detection)',
                'ASPECT 3 - WORKFLOW COVERAGE: Select agent for workflow analysis (explore for flow tracing)',
                'ASPECT 4 - TEST CONSISTENCY: Select agent for consistency analysis (explore for pattern matching)',
                'ASPECT 5 - TEST ISOLATION: Select agent for isolation analysis (explore for dependency scanning)',
                'ASPECT 6 - TEST EXECUTION: Select agent capable of running tests (explore with bash access)',
            ]))
            ->phase(Store::as('SELECTED_AGENTS', '{aspect: agent_id mapping based on $AVAILABLE_AGENTS}'))
            ->phase(Operator::output([
                'Selected agents for test validation:',
                '{$SELECTED_AGENTS mapping}',
                '',
                'Launching test validation agents in parallel...',
            ]))
            ->phase('PARALLEL BATCH: Launch selected agents simultaneously with DEEP RESEARCH tasks')
            ->phase(Operator::do([
                TaskTool::agent('{$SELECTED_AGENTS.coverage}', 'DEEP RESEARCH - REQUIREMENTS COVERAGE for "{$TASK_DESCRIPTION}": 1) Search vector memory for past requirement-test mappings 2) Compare {$DOCUMENTATION_REQUIREMENTS} against {$DISCOVERED_TESTS} 3) For each requirement verify test exists 4) Return: [{requirement_id, coverage_status: covered|partial|missing, test_file, test_method, gap_description, memory_refs}]. Store findings.'),
                TaskTool::agent('{$SELECTED_AGENTS.quality}', 'DEEP RESEARCH - TEST QUALITY for "{$TASK_DESCRIPTION}": 1) Search memory for test quality standards 2) Analyze {$DISCOVERED_TESTS} for bloat indicators 3) Check: excessive mocking, implementation testing, redundant assertions, copy-paste 4) Return: [{test_file, test_method, bloat_type, severity, suggestion}]. Store findings.'),
                TaskTool::agent('{$SELECTED_AGENTS.workflow}', 'DEEP RESEARCH - WORKFLOW COVERAGE for "{$TASK_DESCRIPTION}": 1) Search memory for workflow patterns 2) Verify {$DISCOVERED_TESTS} cover complete user workflows 3) Check: happy path, error paths, edge cases, boundaries 4) Return: [{workflow, coverage_status, missing_scenarios}]. Store findings.'),
                TaskTool::agent('{$SELECTED_AGENTS.consistency}', 'DEEP RESEARCH - TEST CONSISTENCY for "{$TASK_DESCRIPTION}": 1) Search memory for project test conventions 2) Check {$DISCOVERED_TESTS} for consistency 3) Verify: naming, structure, assertions, fixtures, setup/teardown 4) Return: [{test_file, inconsistency_type, description, suggestion}]. Store findings.'),
                TaskTool::agent('{$SELECTED_AGENTS.isolation}', 'DEEP RESEARCH - TEST ISOLATION for "{$TASK_DESCRIPTION}": 1) Search memory for isolation issues 2) Verify {$DISCOVERED_TESTS} are properly isolated 3) Check: shared state, order dependency, external calls, cleanup 4) Return: [{test_file, isolation_issue, severity, suggestion}]. Store findings.'),
                TaskTool::agent('{$SELECTED_AGENTS.execution}', 'DEEP RESEARCH - TEST EXECUTION for "{$TASK_DESCRIPTION}": 1) Search memory for past test failures 2) Run tests related to task 3) Identify flaky tests 4) Return: [{test_file, execution_status: pass|fail|flaky, error_message, execution_time}]. Store findings.'),
            ]))
            ->phase(Store::as('VALIDATION_BATCH_1', '{results from all agents}'))
            ->phase(Operator::output([
                'Batch complete: {$SELECTED_AGENTS.count} test validation checks finished',
            ]));

        // Phase 4: Results Aggregation and Analysis
        $this->guideline('phase4-results-aggregation')
            ->goal('Aggregate all test validation results and categorize issues')
            ->example()
            ->phase(Operator::output([
                '',
                '=== PHASE 4: RESULTS AGGREGATION ===',
            ]))
            ->phase('Merge results from all validation agents')
            ->phase(Store::as('ALL_TEST_ISSUES', '{merged issues from all agents}'))
            ->phase('Categorize issues:')
            ->phase(Store::as('MISSING_COVERAGE', '{requirements without tests}'))
            ->phase(Store::as('PARTIAL_COVERAGE', '{requirements with incomplete tests}'))
            ->phase(Store::as('BLOATED_TESTS', '{tests flagged for bloat}'))
            ->phase(Store::as('MISSING_WORKFLOWS', '{workflows without end-to-end coverage}'))
            ->phase(Store::as('INCONSISTENT_TESTS', '{tests with consistency issues}'))
            ->phase(Store::as('ISOLATION_ISSUES', '{tests with isolation problems}'))
            ->phase(Store::as('FAILING_TESTS', '{tests that fail or are flaky}'))
            ->phase(Operator::output([
                'Test validation results:',
                '- Missing coverage: {$MISSING_COVERAGE.count} requirements',
                '- Partial coverage: {$PARTIAL_COVERAGE.count} requirements',
                '- Bloated tests: {$BLOATED_TESTS.count} tests',
                '- Missing workflows: {$MISSING_WORKFLOWS.count} workflows',
                '- Inconsistent tests: {$INCONSISTENT_TESTS.count} tests',
                '- Isolation issues: {$ISOLATION_ISSUES.count} tests',
                '- Failing/flaky tests: {$FAILING_TESTS.count} tests',
            ]));

        // Phase 5: Task Creation for Test Gaps (Consolidated 5-8h Tasks)
        $this->guideline('phase5-task-creation')
            ->goal('Create consolidated tasks (5-8h each) for test gaps with comprehensive context')
            ->example()
            ->phase(Operator::output([
                '',
                '=== PHASE 5: TASK CREATION (CONSOLIDATED) ===',
            ]))
            ->phase('Check existing tasks to avoid duplicates')
            ->phase(VectorTaskMcp::call('task_list', '{query: "test {$TASK_DESCRIPTION}", limit: 20}'))
            ->phase(Store::as('EXISTING_TEST_TASKS', 'Existing test tasks'))
            ->phase('CONSOLIDATION STRATEGY: Group issues into 5-8 hour task batches')
            ->phase(Operator::do([
                'Calculate total estimate for ALL issues:',
                '- Missing coverage: ~2h per requirement (tests + assertions)',
                '- Failing tests: ~1h per test (debug + fix)',
                '- Bloated tests: ~1.5h per test (refactor + verify)',
                '- Missing workflows: ~3h per workflow (e2e test suite)',
                '- Isolation issues: ~1h per test (refactor + verify)',
                Store::as('TOTAL_ESTIMATE', '{sum of all issue estimates in hours}'),
            ]))
            ->phase(Operator::if('$TOTAL_ESTIMATE <= 8', [
                'ALL issues fit into ONE consolidated task (5-8h range)',
                Operator::if('$ALL_TEST_ISSUES.count > 0 AND NOT exists similar in $EXISTING_TEST_TASKS', [
                    VectorTaskMcp::call('task_create', '{
                        title: "Test fixes: {$TASK_DESCRIPTION}",
                        content: "Consolidated test validation findings for {$TASK_DESCRIPTION}.\\n\\nTotal estimate: {$TOTAL_ESTIMATE}h\\n\\n## Missing Coverage ({$MISSING_COVERAGE.count})\\n{FOR each req: - {req.description} | Type: {req.expected_test_type} | File: {req.related_file}:{req.line} | Scenarios: {req.testable_scenarios}}\\n\\n## Failing Tests ({$FAILING_TESTS.count})\\n{FOR each test: - {test.test_file}:{test.test_method} | Error: {test.error_message} | Status: {test.execution_status}}\\n\\n## Bloated Tests ({$BLOATED_TESTS.count})\\n{FOR each test: - {test.test_file}:{test.test_method} | Bloat: {test.bloat_type} | Suggestion: {test.suggestion}}\\n\\n## Missing Workflows ({$MISSING_WORKFLOWS.count})\\n{FOR each wf: - {wf.workflow} | Missing: {wf.missing_scenarios}}\\n\\n## Isolation Issues ({$ISOLATION_ISSUES.count})\\n{FOR each test: - {test.test_file} | Issue: {test.isolation_issue} | Fix: {test.suggestion}}\\n\\n## Context References\\n- Memory IDs: {$TEST_MEMORY_CONTEXT.memory_ids}\\n- Related tasks: {$RELATED_TEST_TASKS.ids}\\n- Documentation: {$DOCS_INDEX.paths}",
                        priority: "high",
                        estimate: {$TOTAL_ESTIMATE},
                        tags: ["test-validation", "consolidated"],
                        parent_id: $TASK_PARENT_ID
                    }'),
                    Store::as('CREATED_TASKS[]', '{task_id}'),
                    Operator::output(['Created consolidated task: Test fixes ({$TOTAL_ESTIMATE}h, {$ALL_TEST_ISSUES.count} issues)']),
                ]),
            ]))
            ->phase(Operator::if('$TOTAL_ESTIMATE > 8', [
                'Split into multiple 5-8h task batches',
                Store::as('BATCH_SIZE', '6'),
                Store::as('NUM_BATCHES', '{ceil($TOTAL_ESTIMATE / 6)}'),
                'Group issues by priority and type into batches of ~6h each',
                Operator::forEach('batch_index in range(1, $NUM_BATCHES)', [
                    Store::as('BATCH_ISSUES', '{slice of issues for this batch, ~6h worth}'),
                    Store::as('BATCH_ESTIMATE', '{sum of batch issue estimates}'),
                    Operator::if('NOT exists similar in $EXISTING_TEST_TASKS', [
                        VectorTaskMcp::call('task_create', '{
                            title: "Test fixes batch {batch_index}/{$NUM_BATCHES}: {$TASK_DESCRIPTION}",
                            content: "Test validation batch {batch_index} of {$NUM_BATCHES} for {$TASK_DESCRIPTION}.\\n\\nBatch estimate: {$BATCH_ESTIMATE}h\\n\\n## Issues in this batch\\n{FOR each issue in $BATCH_ISSUES:\\n### {issue.type}: {issue.title}\\n- File: {issue.file}:{issue.line}\\n- Description: {issue.description}\\n- Severity: {issue.severity}\\n- Suggestion: {issue.suggestion}\\n- Related memory: {issue.memory_refs}\\n}\\n\\n## Full Context References\\n- Parent task: #{$VECTOR_TASK_ID}\\n- Memory IDs: {$TEST_MEMORY_CONTEXT.memory_ids}\\n- Related tasks: {$RELATED_TEST_TASKS.ids}\\n- Documentation: {$DOCS_INDEX.paths}\\n- Total batches: {$NUM_BATCHES} ({$TOTAL_ESTIMATE}h total)",
                            priority: "{batch_index === 1 ? high : medium}",
                            estimate: {$BATCH_ESTIMATE},
                            tags: ["test-validation", "batch-{batch_index}"],
                            parent_id: $TASK_PARENT_ID
                        }'),
                        Store::as('CREATED_TASKS[]', '{task_id}'),
                        Operator::output(['Created batch {batch_index}/{$NUM_BATCHES}: {$BATCH_ESTIMATE}h']),
                    ]),
                ]),
            ]))
            ->phase(Operator::output([
                'Tasks created: {$CREATED_TASKS.count} (total estimate: {$TOTAL_ESTIMATE}h)',
            ]));

        // Task Consolidation Rules
        $this->rule('task-size-5-8h')->high()
            ->text('Each created task MUST have estimate between 5-8 hours. Never create tasks < 5h (consolidate) or > 8h (split).')
            ->why('Optimal task size for focused work sessions. Too small = context switching overhead. Too large = hard to track progress.')
            ->onViolation('Merge small issues into consolidated task OR split large task into 5-8h batches.');

        $this->rule('task-comprehensive-context')->critical()
            ->text('Each task MUST include: all file:line references, memory IDs, related task IDs, documentation paths, detailed issue descriptions with suggestions.')
            ->why('Enables full context restoration without re-exploration. Saves agent time on task pickup.')
            ->onViolation('Add missing context references before creating task.');

        // Phase 6: Test Validation Completion
        $this->guideline('phase6-completion')
            ->goal('Complete test validation, update task status, store summary to memory')
            ->example()
            ->phase(Operator::output([
                '',
                '=== PHASE 6: TEST VALIDATION COMPLETE ===',
            ]))
            ->phase(Store::as('COVERAGE_RATE', '{covered_requirements / total_requirements * 100}%'))
            ->phase(Store::as('TEST_HEALTH_SCORE', '{100 - (bloat_count + isolation_count + failing_count) / total_tests * 100}%'))
            ->phase(Store::as('VALIDATION_STATUS', Operator::if('$MISSING_COVERAGE.count === 0 AND $FAILING_TESTS.count === 0', 'PASSED', 'NEEDS_WORK')))
            ->phase(VectorMemoryMcp::call('store_memory', '{content: "Test validation of {$TASK_DESCRIPTION}\\n\\nStatus: {$VALIDATION_STATUS}\\nCoverage rate: {$COVERAGE_RATE}\\nTest health: {$TEST_HEALTH_SCORE}\\n\\nMissing coverage: {$MISSING_COVERAGE.count}\\nFailing tests: {$FAILING_TESTS.count}\\nBloated tests: {$BLOATED_TESTS.count}\\nTasks created: {$CREATED_TASKS.count}\\n\\nKey findings: {summary}", category: "code-solution", tags: ["test-validation", "audit"]}'))
            ->phase(Operator::if('$IS_VECTOR_TASK === true', [
                Operator::if('$VALIDATION_STATUS === "PASSED" AND $CREATED_TASKS.count === 0', [
                    VectorTaskMcp::call('task_update', '{task_id: $VECTOR_TASK_ID, status: "tested", comment: "Test validation PASSED. All requirements covered, all tests passing, no critical issues.", append_comment: true}'),
                    Operator::output(['✅ Task #{$VECTOR_TASK_ID} marked as TESTED']),
                ]),
                Operator::if('$CREATED_TASKS.count > 0', [
                    VectorTaskMcp::call('task_update', '{task_id: $VECTOR_TASK_ID, status: "pending", comment: "Test validation found issues. Coverage: {$COVERAGE_RATE}, Health: {$TEST_HEALTH_SCORE}. Created {$CREATED_TASKS.count} fix tasks. Returning to pending - fix tasks must be completed before re-testing.", append_comment: true}'),
                    Operator::output(['⏳ Task #{$VECTOR_TASK_ID} returned to PENDING ({$CREATED_TASKS.count} fix tasks required before re-testing)']),
                ]),
            ]))
            ->phase(Operator::output([
                '',
                '=== TEST VALIDATION REPORT ===',
                'Task: {$TASK_DESCRIPTION}',
                'Status: {$VALIDATION_STATUS}',
                '',
                '| Metric | Value |',
                '|--------|-------|',
                '| Requirements coverage | {$COVERAGE_RATE} |',
                '| Test health score | {$TEST_HEALTH_SCORE} |',
                '| Total tests | {$DISCOVERED_TESTS.count} |',
                '| Passing tests | {passing_count} |',
                '| Failing/flaky tests | {$FAILING_TESTS.count} |',
                '',
                '| Issue Type | Count |',
                '|------------|-------|',
                '| Missing coverage | {$MISSING_COVERAGE.count} |',
                '| Partial coverage | {$PARTIAL_COVERAGE.count} |',
                '| Bloated tests | {$BLOATED_TESTS.count} |',
                '| Missing workflows | {$MISSING_WORKFLOWS.count} |',
                '| Isolation issues | {$ISOLATION_ISSUES.count} |',
                '',
                'Tasks created: {$CREATED_TASKS.count}',
                '{IF $CREATED_TASKS.count > 0: "Follow-up tasks: {$CREATED_TASKS}"}',
                '',
                'Test validation stored to vector memory.',
            ]));

        // Error Handling
        $this->guideline('error-handling')
            ->text('Graceful error handling for test validation process')
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
                'Continue with test-only validation (existing tests analysis)',
                'Note: "Cannot verify requirements coverage without documentation"',
            ])
            ->phase()->if('no tests found', [
                'Report: "No tests found for {$TASK_DESCRIPTION}"',
                'Create task: "Write initial tests for {$TASK_DESCRIPTION}"',
                'Continue with documentation requirements analysis',
            ])
            ->phase()->if('test execution fails', [
                'Log: "Test execution failed: {error}"',
                'Mark tests as "execution_unknown"',
                'Continue with static analysis',
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

        // Test Quality Criteria
        $this->guideline('test-quality-criteria')
            ->text('Criteria for evaluating test quality (bloat detection)')
            ->example()
            ->phase('BLOAT INDICATORS (flag for refactoring):')
            ->do([
                'Excessive mocking (>3 mocks per test)',
                'Testing private methods directly',
                'Testing getters/setters without logic',
                'Copy-paste test code (>80% similarity)',
                'Single assertion tests without context',
                'Testing framework internals',
                'Hard-coded magic values without explanation',
                'Test method >50 lines',
                'Setup >30 lines',
            ])
            ->phase('QUALITY INDICATORS (good tests):')
            ->do([
                'Tests behavior, not implementation',
                'Readable test names (given_when_then)',
                'Single responsibility per test',
                'Proper use of fixtures/factories',
                'Edge cases covered',
                'Error paths tested',
                'Fast execution (<100ms per test)',
                'No external dependencies without mocks',
            ]);

        // Constraints and Validation
        $this->guideline('constraints')
            ->text('Test validation constraints and limits')
            ->example()
            ->phase('Max 6 parallel validation agents per batch')
            ->phase('Max 30 tasks created per validation run')
            ->phase('Test execution timeout: 5 minutes total')
            ->phase('Bloat threshold: >50% bloated = critical warning')
            ->phase(Operator::verify([
                'completed_status_enforced = true (for vector tasks)',
                'parallel_agents_used = true',
                'documentation_checked = true',
                'tests_executed = true',
                'results_stored_to_memory = true',
            ]));

        // Examples
        $this->guideline('example-vector-task')
            ->scenario('Test validate completed vector task')
            ->example()
            ->phase('input', '"task 15" or "test-validate task:15"')
            ->phase('detection', 'Task #15 loaded, status: completed')
            ->phase('flow', 'Task Detection → Context → Docs → Test Discovery → Parallel Validation (6 agents) → Aggregate → Create Tasks → Complete')
            ->phase('result', 'Test validation PASSED/NEEDS_WORK, coverage %, N tasks created');

        $this->guideline('example-plain-request')
            ->scenario('Test validate work by description')
            ->example()
            ->phase('input', '"test-validate user authentication"')
            ->phase('flow', 'Context from memory → Docs requirements → Test Discovery → Parallel Validation → Aggregate → Create Tasks → Report')
            ->phase('result', 'Test validation report with coverage metrics and created tasks');

        $this->guideline('example-rerun')
            ->scenario('Re-run test validation (idempotent)')
            ->example()
            ->phase('input', '"task 15" (already test-validated before)')
            ->phase('behavior', 'Skips existing tasks, only creates NEW issues found')
            ->phase('result', 'Same/updated validation report, no duplicate tasks');

        // Response Format
        $this->guideline('response-format')
            ->text('=== headers | Parallel: agent batch indicators | Tables: coverage metrics + issue counts | Coverage % | Health score | Created tasks listed');
    }
}