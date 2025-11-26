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
            ->text('For vector tasks: ONLY tasks with status "completed" can be test-validated. Pending/in_progress tasks MUST first be completed via do:async.')
            ->why('Test validation audits finished work. Incomplete work cannot be validated.')
            ->onViolation('Report: "Task #{id} has status {status}. Complete via /do:async first."');

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
                Operator::if('$VECTOR_TASK.status !== "completed"', [
                    Operator::output([
                        '=== TEST VALIDATION BLOCKED ===',
                        'Task #{$VECTOR_TASK_ID} has status: {$VECTOR_TASK.status}',
                        'Only COMPLETED tasks can be test-validated.',
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

        // Phase 0: Test Validation Preview
        $this->guideline('phase0-validation-preview')
            ->goal('Preview test validation scope and get user approval before starting')
            ->example()
            ->phase(Operator::output([
                '=== PHASE 0: TEST VALIDATION PREVIEW ===',
            ]))
            ->phase(VectorMemoryMcp::call('search_memories', '{query: "tests for: {$TASK_DESCRIPTION}", limit: 3, category: "code-solution"}'))
            ->phase(Store::as('TEST_PREVIEW', 'Test preview'))
            ->phase(VectorTaskMcp::call('task_list', '{query: "test {$TASK_DESCRIPTION}", limit: 5}'))
            ->phase(Store::as('RELATED_TEST_TASKS_PREVIEW', 'Related test tasks preview'))
            ->phase(BashTool::describe(BrainCLI::DOCS('{keywords from $TASK_DESCRIPTION}'), 'Get documentation INDEX preview'))
            ->phase(Store::as('DOCS_PREVIEW', 'Documentation files available'))
            ->phase(Operator::output([
                'Task: {$TASK_DESCRIPTION}',
                'Related test memories: {$TEST_PREVIEW.count}',
                'Related test tasks: {$RELATED_TEST_TASKS_PREVIEW.count}',
                'Documentation files: {$DOCS_PREVIEW.count}',
                '',
                'Test validation will check:',
                '1. Requirements coverage by tests',
                '2. Test quality (no bloat)',
                '3. Workflow coverage (end-to-end)',
                '4. Test consistency',
                '5. Test isolation',
                '6. Test execution status',
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

        // Phase 1: Deep Test Context Gathering
        $this->guideline('phase1-context-gathering')
            ->goal('Gather test-related context from vector memory after approval')
            ->example()
            ->phase(Operator::output([
                '',
                '=== PHASE 1: DEEP TEST CONTEXT ===',
            ]))
            ->phase(VectorMemoryMcp::call('search_memories', '{query: "tests for: {$TASK_DESCRIPTION}", limit: 5, category: "code-solution"}'))
            ->phase(Store::as('TEST_HISTORY', 'Past test implementations'))
            ->phase(VectorMemoryMcp::call('search_memories', '{query: "test patterns: {$TASK_DESCRIPTION}", limit: 5, category: "learning"}'))
            ->phase(Store::as('TEST_PATTERNS', 'Test patterns and best practices'))
            ->phase(VectorTaskMcp::call('task_list', '{query: "test {$TASK_DESCRIPTION}", limit: 10}'))
            ->phase(Store::as('RELATED_TEST_TASKS', 'Related test tasks'))
            ->phase(Operator::output([
                'Context gathered:',
                '- Test history: {$TEST_HISTORY.count} memories',
                '- Test patterns: {$TEST_PATTERNS.count} memories',
                '- Related test tasks: {$RELATED_TEST_TASKS.count} tasks',
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

        // Phase 2: Test Discovery
        $this->guideline('phase2-test-discovery')
            ->goal('Discover all existing tests related to the task')
            ->example()
            ->phase(Operator::output([
                '',
                '=== PHASE 2: TEST DISCOVERY ===',
            ]))
            ->phase(TaskTool::agent('explore', 'Find ALL test files related to "{$TASK_DESCRIPTION}". Search in: tests/, spec/, __tests__. Include: unit tests, feature tests, integration tests, e2e tests. Return: [{test_file, test_type, test_classes: [...], test_methods: [...], related_source_files: [...]}]. Store to vector memory.'))
            ->phase(Store::as('DISCOVERED_TESTS', '{list of test files with metadata}'))
            ->phase(Operator::output([
                'Tests discovered: {$DISCOVERED_TESTS.count} files',
                '{test files summary by type}',
            ]));

        // Phase 3: Parallel Test Validation
        $this->guideline('phase3-parallel-validation')
            ->goal('Launch 6 parallel agents to validate different test aspects simultaneously')
            ->example()
            ->phase(Operator::output([
                '',
                '=== PHASE 3: PARALLEL TEST VALIDATION ===',
                'Launching test validation agents in parallel...',
            ]))
            ->phase('PARALLEL BATCH 1: Launch ALL agents simultaneously')
            ->phase(Operator::do([
                TaskTool::agent('explore', 'TEST AGENT 1 - REQUIREMENTS COVERAGE: Compare {$DOCUMENTATION_REQUIREMENTS} against {$DISCOVERED_TESTS}. For each requirement verify test exists. Return: [{requirement_id, coverage_status: covered|partial|missing, test_file: or null, test_method: or null, gap_description}]. Store findings to vector memory.'),
                TaskTool::agent('explore', 'TEST AGENT 2 - TEST QUALITY (NO BLOAT): Analyze {$DISCOVERED_TESTS} for bloat indicators: excessive mocking, testing implementation details, redundant assertions, copy-paste tests, testing getters/setters. Return: [{test_file, test_method, bloat_type, severity: minor|major, suggestion}]. Store findings to vector memory.'),
                TaskTool::agent('explore', 'TEST AGENT 3 - WORKFLOW COVERAGE: Verify {$DISCOVERED_TESTS} cover COMPLETE user workflows end-to-end. Check: happy path, error paths, edge cases, boundary conditions. Return: [{workflow, coverage_status, missing_scenarios: [...]}]. Store findings to vector memory.'),
                TaskTool::agent('explore', 'TEST AGENT 4 - TEST CONSISTENCY: Check {$DISCOVERED_TESTS} for consistency: naming conventions, structure patterns, assertion styles, fixture usage, setup/teardown patterns. Return: [{test_file, inconsistency_type, description, suggestion}]. Store findings to vector memory.'),
                TaskTool::agent('explore', 'TEST AGENT 5 - TEST ISOLATION: Verify {$DISCOVERED_TESTS} are properly isolated: no shared state, no order dependency, no external service calls without mocks, proper cleanup. Return: [{test_file, isolation_issue, severity, suggestion}]. Store findings to vector memory.'),
                TaskTool::agent('explore', 'TEST AGENT 6 - TEST EXECUTION: Run tests related to "{$TASK_DESCRIPTION}". Verify all pass. Identify flaky tests. Return: [{test_file, execution_status: pass|fail|flaky, error_message: if fail, execution_time}]. Store findings to vector memory.'),
            ]))
            ->phase(Store::as('VALIDATION_BATCH_1', '{results from all 6 agents}'))
            ->phase(Operator::output([
                'Batch 1 complete: 6 test validation checks finished',
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

        // Phase 5: Task Creation for Test Gaps
        $this->guideline('phase5-task-creation')
            ->goal('Create tasks for missing tests, refactoring needs, and fixes')
            ->example()
            ->phase(Operator::output([
                '',
                '=== PHASE 5: TASK CREATION ===',
            ]))
            ->phase('Check existing tasks to avoid duplicates')
            ->phase(VectorTaskMcp::call('task_list', '{query: "test {$TASK_DESCRIPTION}", limit: 20}'))
            ->phase(Store::as('EXISTING_TEST_TASKS', 'Existing test tasks'))
            ->phase('Create tasks for MISSING COVERAGE (highest priority)')
            ->phase(Operator::forEach('req in $MISSING_COVERAGE', [
                Operator::if('NOT exists in $EXISTING_TEST_TASKS', [
                    VectorTaskMcp::call('task_create', '{title: "Write tests: {req.description}", content: "Missing test coverage found during test validation.\\n\\nRequirement: {req.description}\\nTestable scenarios: {req.testable_scenarios}\\nAcceptance criteria: {req.acceptance_criteria}\\nExpected test type: {req.expected_test_type}\\n\\nFocus on BEHAVIOR, not implementation details.", priority: "high", tags: ["test-coverage", "missing"], parent_id: $VECTOR_TASK_ID or null}'),
                    Store::as('CREATED_TASKS[]', '{task_id}'),
                    Operator::output(['Created task: Write tests for {req.description}']),
                ]),
            ]))
            ->phase('Create tasks for FAILING TESTS (high priority)')
            ->phase(Operator::forEach('test in $FAILING_TESTS', [
                Operator::if('NOT exists in $EXISTING_TEST_TASKS', [
                    VectorTaskMcp::call('task_create', '{title: "Fix failing test: {test.test_file}", content: "Failing/flaky test found during test validation.\\n\\nFile: {test.test_file}\\nStatus: {test.execution_status}\\nError: {test.error_message}\\n\\nInvestigate root cause and fix.", priority: "high", tags: ["test-fix", "failing"], parent_id: $VECTOR_TASK_ID or null}'),
                    Store::as('CREATED_TASKS[]', '{task_id}'),
                    Operator::output(['Created task: Fix {test.test_file}']),
                ]),
            ]))
            ->phase('Create tasks for BLOATED TESTS (medium priority)')
            ->phase(Operator::forEach('test in $BLOATED_TESTS where severity === "major"', [
                Operator::if('NOT exists in $EXISTING_TEST_TASKS', [
                    VectorTaskMcp::call('task_create', '{title: "Refactor bloated test: {test.test_file}:{test.test_method}", content: "Bloated test found during test validation.\\n\\nFile: {test.test_file}\\nMethod: {test.test_method}\\nBloat type: {test.bloat_type}\\nSuggestion: {test.suggestion}\\n\\nSimplify to test BEHAVIOR, not implementation.", priority: "medium", tags: ["test-refactor", "bloat"], parent_id: $VECTOR_TASK_ID or null}'),
                    Store::as('CREATED_TASKS[]', '{task_id}'),
                    Operator::output(['Created task: Refactor bloated test {test.test_method}']),
                ]),
            ]))
            ->phase('Create tasks for MISSING WORKFLOWS (medium priority)')
            ->phase(Operator::forEach('workflow in $MISSING_WORKFLOWS', [
                Operator::if('NOT exists in $EXISTING_TEST_TASKS', [
                    VectorTaskMcp::call('task_create', '{title: "Add workflow test: {workflow.workflow}", content: "Missing end-to-end workflow test found during validation.\\n\\nWorkflow: {workflow.workflow}\\nMissing scenarios: {workflow.missing_scenarios}\\n\\nWrite tests that cover complete user journey.", priority: "medium", tags: ["test-coverage", "workflow"], parent_id: $VECTOR_TASK_ID or null}'),
                    Store::as('CREATED_TASKS[]', '{task_id}'),
                    Operator::output(['Created task: Add workflow test for {workflow.workflow}']),
                ]),
            ]))
            ->phase('Create tasks for ISOLATION ISSUES (medium priority)')
            ->phase(Operator::forEach('test in $ISOLATION_ISSUES where severity === "major"', [
                Operator::if('NOT exists in $EXISTING_TEST_TASKS', [
                    VectorTaskMcp::call('task_create', '{title: "Fix test isolation: {test.test_file}", content: "Test isolation issue found during validation.\\n\\nFile: {test.test_file}\\nIssue: {test.isolation_issue}\\nSuggestion: {test.suggestion}\\n\\nEnsure test can run independently.", priority: "medium", tags: ["test-fix", "isolation"], parent_id: $VECTOR_TASK_ID or null}'),
                    Store::as('CREATED_TASKS[]', '{task_id}'),
                    Operator::output(['Created task: Fix isolation in {test.test_file}']),
                ]),
            ]))
            ->phase(Operator::output([
                'Tasks created: {$CREATED_TASKS.count}',
            ]));

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
                Operator::if('$VALIDATION_STATUS === "PASSED"', [
                    VectorTaskMcp::call('task_update', '{task_id: $VECTOR_TASK_ID, status: "completed", comment: "Test validation PASSED. All requirements covered, all tests passing, no critical issues.", append_comment: true}'),
                ]),
                Operator::if('$VALIDATION_STATUS === "NEEDS_WORK"', [
                    VectorTaskMcp::call('task_update', '{task_id: $VECTOR_TASK_ID, status: "completed", comment: "Test validation completed with findings. Coverage: {$COVERAGE_RATE}, Health: {$TEST_HEALTH_SCORE}. Created {$CREATED_TASKS.count} follow-up tasks.", append_comment: true}'),
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