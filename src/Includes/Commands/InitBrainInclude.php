<?php

declare(strict_types=1);

namespace BrainCore\Includes\Commands;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;
use BrainCore\Compilation\BrainCLI;
use BrainCore\Compilation\Operator;
use BrainCore\Compilation\Runtime;
use BrainCore\Compilation\Store;
use BrainCore\Compilation\Tools\BashTool;
use BrainCore\Compilation\Tools\ReadTool;
use BrainCore\Compilation\Tools\WebSearchTool;
use BrainNode\Agents\AgentMaster;
use BrainNode\Agents\DocumentationMaster;
use BrainNode\Agents\ExploreMaster;
use BrainNode\Agents\PromptMaster;
use BrainNode\Agents\WebResearchMaster;
use BrainNode\Mcp\VectorMemoryMcp;

#[Purpose('The InitBrain command automates smart distribution of project-specific configuration across Brain.php, Common.php, and Master.php based on project context discovery.')]
class InitBrainInclude extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     *
     * @return void
     */
    protected function handle(): void
    {
        // =====================================================
        // IRON RULES
        // =====================================================

        $this->rule('temporal-context-first')->critical()
            ->text(['Temporal context MUST be initialized first:', BashTool::call('date +"%Y-%m-%d %H:%M:%S %Z"')])
            ->why('Ensures all research and recommendations reflect current year best practices')
            ->onViolation('Missing temporal context leads to outdated recommendations');

        $this->rule('parallel-research')->critical()
            ->text('Execute independent research tasks in parallel for efficiency')
            ->why('Maximizes throughput and minimizes total execution time')
            ->onViolation('Sequential execution wastes time on independent tasks');

        $this->rule('evidence-based')->critical()
            ->text('All Brain.php guidelines must be backed by discovered project evidence')
            ->why('Prevents generic configurations that do not match project reality')
            ->onViolation('Speculation leads to misaligned Brain behavior');

        $this->rule('preserve-existing')->critical()
            ->text(['Backup existing', Runtime::NODE_DIRECTORY('Brain.php'), 'before modifications'])
            ->why('Prevents data loss and enables rollback if needed')
            ->onViolation('Data loss and inability to recover previous configuration');

        $this->rule('vector-memory-storage')->high()
            ->text('Store all significant insights to vector memory with semantic tags')
            ->why('Enables future context retrieval and knowledge accumulation')
            ->onViolation('Knowledge loss and inability to leverage past discoveries');

        $this->rule('preserve-variation')->critical()
            ->text([
                'NEVER modify or replace existing #[Includes()] attributes on Brain.php',
                'Brain already has a Variation (e.g., Scrutinizer) - preserve it',
                'Standard includes from vendor/jarvis-brain/core/src/Includes are OFF LIMITS',
            ])
            ->why('Variations are pre-configured brain personalities with carefully tuned includes')
            ->onViolation('Modifying Variation breaks brain coherence and predefined behavior');

        $this->rule('project-includes-only')->critical()
            ->text([
                'Only analyze and suggest includes from ' . Runtime::NODE_DIRECTORY('Includes/'),
                'FORBIDDEN: vendor/jarvis-brain/core/src/Includes/* modifications',
                'FORBIDDEN: Replacing or adding standard includes to Brain.php',
            ])
            ->why('Standard includes are managed by Variations, not by init process')
            ->onViolation('Standard includes are bundled with Variation - do not duplicate or override');

        $this->rule('smart-distribution')->critical()
            ->text([
                'Distribute project-specific rules across THREE files to avoid duplication:',
                Runtime::NODE_DIRECTORY('Common.php') . ' - Shared by Brain AND all Agents',
                Runtime::NODE_DIRECTORY('Master.php') . ' - Shared by ALL Agents only (NOT Brain)',
                Runtime::NODE_DIRECTORY('Brain.php') . ' - Brain-specific only',
            ])
            ->why('Prevents duplication across components, ensures single source of truth for each rule type')
            ->onViolation('Rule placed in wrong file causes duplication or missing context');

        $this->rule('distribution-categories')->critical()
            ->text([
                'COMMON: Environment (Docker, CI/CD), project tech stack, universal coding standards, shared config',
                'MASTER: Agent execution patterns, tool usage constraints, agent-specific guidelines, task handling',
                'BRAIN: Orchestration rules, delegation strategies, Brain-specific policies, workflow coordination',
            ])
            ->why('Clear categorization ensures each file serves its specific purpose without overlap')
            ->onViolation('Miscategorized rule leads to missing context or unnecessary duplication');

        // =====================================================
        // PHASE 1: TEMPORAL CONTEXT INITIALIZATION
        // =====================================================

        $this->guideline('phase1-temporal-context')
            ->goal('Initialize temporal awareness for all subsequent operations')
            ->example()
            ->phase(
                BashTool::describe('date +"%Y-%m-%d"', Store::as('CURRENT_DATE'))
            )
            ->phase(
                BashTool::describe('date +"%Y"', Store::as('CURRENT_YEAR'))
            )
            ->phase(
                BashTool::describe('date +"%Y-%m-%d %H:%M:%S %Z"', Store::as('TIMESTAMP'))
            )
            ->phase(Operator::verify('All temporal variables set'))
            ->note('This ensures all research queries include current year for up-to-date results');

        // =====================================================
        // PHASE 2: PROJECT DISCOVERY (PARALLEL)
        // =====================================================

        $this->guideline('phase2-project-discovery')
            ->goal('Discover project structure, technology stack, and patterns')
            ->example()
            ->note('Execute all discovery tasks in parallel for efficiency')
            ->phase()
            ->name('parallel-discovery-tasks')
            ->do(
                Operator::task([
                    // Task 2.1: Documentation Discovery
                    ExploreMaster::call(
                        Operator::task([
                            'Check if .docs/ directory exists using Glob',
                            'Use Glob("**/.docs/**/*.md") to find documentation files',
                            Operator::if('.docs/ exists', [
                                'Read all .md files from .docs/ directory',
                                'Extract: project goals, requirements, architecture decisions, domain terminology',
                                Store::as('DOCS_CONTENT'),
                            ], [
                                'No .docs/ found',
                                Store::as('DOCS_CONTENT', 'null'),
                            ]),
                        ]),
                        Operator::context('Documentation discovery for project context')
                    ),

                    // Task 2.2: Codebase Structure Analysis
                    ExploreMaster::call(
                        Operator::task([
                            'Analyze project root structure',
                            'Use Glob to find: composer.json, package.json, .env.example, README.md',
                            'Read key dependency files',
                            'Identify project type (Laravel, Node.js, hybrid, etc.)',
                            'Extract technology stack from dependency files',
                            Store::as('PROJECT_TYPE'),
                            Store::as('TECH_STACK', '{languages: [...], frameworks: [...], packages: [...], services: [...]}'),
                        ]),
                        Operator::context('Codebase structure and tech stack analysis')
                    ),

                    // Task 2.3: Architecture Pattern Discovery
                    ExploreMaster::call(
                        Operator::task([
                            'Scan for architectural patterns',
                            'Use Glob to find PHP/JS/TS files in app/ and src/ directories',
                            'Analyze code structure and organization',
                            'Identify: MVC, DDD, CQRS, microservices, monolith, etc.',
                            'Detect design patterns: repositories, services, factories, observers, etc.',
                            'Find coding conventions: naming, structure, organization',
                            Store::as('ARCHITECTURE_PATTERNS', '{architecture_style: "...", design_patterns: [...], conventions: [...]}'),
                        ]),
                        Operator::context('Architecture pattern discovery')
                    ),

                    // Task 2.4: Existing Brain Configuration Analysis
                    ExploreMaster::call(
                        Operator::task([
                            ReadTool::call(Runtime::NODE_DIRECTORY('Brain.php')),
                            'Extract current includes and configuration',
                            'Identify what is already configured',
                            Store::as('CURRENT_BRAIN_CONFIG', '{includes: [...], custom_rules: [...], custom_guidelines: [...]}'),
                        ]),
                        Operator::context('Current Brain configuration analysis')
                    ),
                ])
            )
            ->phase(Operator::verify('All discovery tasks completed'))
            ->phase(Store::as('PROJECT_CONTEXT', 'Merged results from all discovery tasks'));

        // =====================================================
        // PHASE 2.5: ENVIRONMENT DISCOVERY (PARALLEL)
        // =====================================================

        $this->guideline('phase2-5-environment-discovery')
            ->goal('Discover environment configuration, containerization, and infrastructure patterns')
            ->example()
            ->note('Environment rules go to Common.php - shared by Brain AND all Agents')
            ->phase()
            ->name('parallel-environment-tasks')
            ->do(
                Operator::task([
                    // Task 2.5.1: Docker/Container Detection
                    ExploreMaster::call(
                        Operator::task([
                            'Use Glob to find: Dockerfile*, docker-compose*.yml, .dockerignore',
                            'Read Docker configurations if found',
                            'Extract: base images, services, ports, volumes, networks',
                            'Identify: container orchestration patterns (Docker Compose, K8s, etc.)',
                            Store::as('DOCKER_CONFIG', '{has_docker: bool, services: [...], patterns: [...]}'),
                        ]),
                        Operator::context('Docker and containerization discovery')
                    ),

                    // Task 2.5.2: CI/CD Detection
                    ExploreMaster::call(
                        Operator::task([
                            'Use Glob to find: .github/workflows/*.yml, .gitlab-ci.yml, Jenkinsfile, bitbucket-pipelines.yml',
                            'Read CI/CD configurations if found',
                            'Extract: build steps, test runners, deployment targets',
                            'Identify: CI/CD platform and workflow patterns',
                            Store::as('CICD_CONFIG', '{platform: "...", workflows: [...], deployment_targets: [...]}'),
                        ]),
                        Operator::context('CI/CD pipeline discovery')
                    ),

                    // Task 2.5.3: Development Environment Detection
                    ExploreMaster::call(
                        Operator::task([
                            'Use Glob to find: .editorconfig, .prettierrc*, .eslintrc*, phpcs.xml*, phpstan.neon*',
                            'Read linter/formatter configurations if found',
                            'Extract: code style rules, linting rules, analysis levels',
                            'Identify: tooling ecosystem (Prettier, ESLint, PHPStan, etc.)',
                            Store::as('DEV_TOOLS_CONFIG', '{formatters: [...], linters: [...], analyzers: [...]}'),
                        ]),
                        Operator::context('Development tooling discovery')
                    ),

                    // Task 2.5.4: Infrastructure/Services Detection
                    ExploreMaster::call(
                        Operator::task([
                            'Use Glob to find: .env.example, config/*.php, infrastructure/*',
                            'Analyze service connections: databases, caches, queues, storage',
                            'Identify: external service dependencies (AWS, GCP, Redis, Elasticsearch)',
                            'Map infrastructure topology',
                            Store::as('INFRASTRUCTURE_CONFIG', '{services: [...], external_deps: [...], topology: {...}}'),
                        ]),
                        Operator::context('Infrastructure and services discovery')
                    ),
                ])
            )
            ->phase(Operator::verify('Environment discovery completed'))
            ->phase(Store::as('ENVIRONMENT_CONTEXT', 'Merged environment configuration'));

        // =====================================================
        // PHASE 3: DOCUMENTATION DEEP ANALYSIS
        // =====================================================

        $this->guideline('phase3-documentation-analysis')
            ->goal('Deep analysis of project documentation to extract requirements and domain knowledge')
            ->example()
            ->phase(
                Operator::if(Store::get('DOCS_CONTENT') . ' !== null', [
                    DocumentationMaster::call(
                        Operator::input(Store::get('DOCS_CONTENT')),
                        Operator::task([
                            'Analyze all documentation files',
                            'Extract: project goals, requirements, constraints, domain concepts',
                            'Identify: key workflows, business rules, integration points',
                            'Map documentation to Brain configuration needs',
                            'Suggest: custom includes, rules, guidelines based on docs',
                        ]),
                        Operator::output('{goals: [...], requirements: [...], domain_concepts: [...], suggested_config: {...}}'),
                    ),
                    Store::as('DOCS_ANALYSIS'),
                ], [
                    'No documentation found - will rely on codebase analysis only',
                    Store::as('DOCS_ANALYSIS', 'null'),
                ])
            );

        // =====================================================
        // PHASE 4: BEST PRACTICES RESEARCH (PARALLEL)
        // =====================================================

        $this->guideline('phase4-best-practices-research')
            ->goal('Research current best practices for discovered technologies')
            ->example()
            ->note('Execute research tasks in parallel for each major technology')
            ->phase(
                Operator::forEach(Store::get('TECH_STACK.frameworks'), [
                    WebResearchMaster::call(
                        Operator::input(Store::get('CURRENT_YEAR')),
                        Operator::task([
                            WebSearchTool::describe('{framework} best practices {current_year}'),
                            WebSearchTool::describe('{framework} architectural patterns {current_year}'),
                            WebSearchTool::describe('{framework} code organization {current_year}'),
                            'Extract: recommended patterns, conventions, anti-patterns',
                            'Identify: framework-specific Brain configuration needs',
                        ]),
                        Operator::output('{framework: "...", best_practices: [...], recommendations: [...]}'),
                    ),
                ])
            )
            ->phase(Store::as('BEST_PRACTICES', 'Collected results from all research tasks'));

        // =====================================================
        // PHASE 5: PROJECT-SPECIFIC INCLUDES ANALYSIS
        // =====================================================

        $this->guideline('phase5-project-includes')
            ->goal('Analyze and suggest PROJECT-SPECIFIC includes only (NOT standard includes)')
            ->note([
                'IMPORTANT: Brain already has a Variation with standard includes configured',
                'This phase focuses ONLY on ' . Runtime::NODE_DIRECTORY('Includes/'),
                'FORBIDDEN: Suggesting or modifying vendor/jarvis-brain/core/src/Includes/*',
            ])
            ->example()
            ->phase(
                ExploreMaster::call(
                    Operator::task([
                        'Scan ' . Runtime::NODE_DIRECTORY('Includes/') . ' for existing project includes',
                        'Read each include file to understand its purpose',
                        'Identify gaps in project-specific configuration',
                    ]),
                    Operator::context('Project-specific includes discovery')
                )
            )
            ->phase(Store::as('EXISTING_PROJECT_INCLUDES'))
            ->phase(
                AgentMaster::call(
                    Operator::input(
                        Store::get('EXISTING_PROJECT_INCLUDES'),
                        Store::get('PROJECT_CONTEXT'),
                        Store::get('DOCS_ANALYSIS'),
                        Store::get('BEST_PRACTICES'),
                    ),
                    Operator::task([
                        'Analyze existing project-specific includes in ' . Runtime::NODE_DIRECTORY('Includes/'),
                        'Map project needs to include capabilities',
                        'Identify MISSING project-specific includes that should be CREATED',
                        'DO NOT suggest standard includes from vendor/jarvis-brain/core/src/Includes',
                        'Generate list of new project includes to create via brain make:include',
                    ]),
                    Operator::output('{existing_project_includes: [...], suggested_new_includes: [...], rationale: {...}}'),
                )
            )
            ->phase(Store::as('PROJECT_INCLUDES_RECOMMENDATION'));

        // =====================================================
        // PHASE 6: SMART DISTRIBUTION CATEGORIZATION
        // =====================================================

        $this->guideline('phase6-smart-distribution')
            ->goal('Categorize discovered rules/guidelines into Common, Master, or Brain files')
            ->note([
                'CRITICAL: Each rule MUST go to exactly ONE file to avoid duplication',
                Runtime::NODE_DIRECTORY('Common.php') . ' - Shared by Brain AND all Agents',
                Runtime::NODE_DIRECTORY('Master.php') . ' - Shared by ALL Agents only',
                Runtime::NODE_DIRECTORY('Brain.php') . ' - Brain-specific only',
            ])
            ->example()
            ->phase(
                AgentMaster::call(
                    Operator::input(
                        Store::get('PROJECT_CONTEXT'),
                        Store::get('ENVIRONMENT_CONTEXT'),
                        Store::get('DOCS_ANALYSIS'),
                        Store::get('BEST_PRACTICES'),
                        Store::get('ARCHITECTURE_PATTERNS'),
                    ),
                    Operator::task([
                        'Analyze ALL discovered project patterns and rules',
                        'CATEGORIZE each rule into exactly ONE target file:',
                        '',
                        'COMMON.PHP (Brain + ALL Agents):',
                        '  - Docker/container environment rules (ports, services, networks)',
                        '  - CI/CD pipeline awareness (test commands, build steps)',
                        '  - Project tech stack rules (PHP version, Node version, database type)',
                        '  - Universal coding standards (naming conventions, file structure)',
                        '  - Shared configuration (env vars, paths, external services)',
                        '  - Development tooling rules (linters, formatters, analyzers)',
                        '',
                        'MASTER.PHP (ALL Agents only, NOT Brain):',
                        '  - Agent execution patterns (how agents should approach tasks)',
                        '  - Tool usage constraints (when to use which tools)',
                        '  - Task handling guidelines (decomposition, estimation, status flow)',
                        '  - Code generation patterns (templates, scaffolding)',
                        '  - Test writing conventions (test structure, coverage expectations)',
                        '  - Agent-specific quality gates (validation before completion)',
                        '',
                        'BRAIN.PHP (Brain-specific only):',
                        '  - Orchestration rules (delegation strategies, agent selection)',
                        '  - Brain-specific policies (approval chains, escalation)',
                        '  - Workflow coordination (multi-agent orchestration)',
                        '  - Response synthesis (how to merge agent results)',
                        '  - Brain-level validation (response quality gates)',
                        '',
                        'Generate PHP Builder API code for each category',
                        'Use $this->rule() for constraints, $this->guideline() for patterns',
                    ]),
                    Operator::output('{common: [{id, type, code}], master: [{id, type, code}], brain: [{id, type, code}], rationale: {...}}'),
                )
            )
            ->phase(Store::as('DISTRIBUTED_GUIDELINES'));

        // =====================================================
        // PHASE 6A: COMMON.PHP ENHANCEMENT
        // =====================================================

        $this->guideline('phase6a-common-enhancement')
            ->goal('Enhance Common.php with shared project rules for Brain AND all Agents')
            ->note([
                'Common.php is included by BOTH BrainIncludesTrait AND AgentIncludesTrait',
                'Rules here apply universally - avoid agent-specific or brain-specific content',
                'Focus: environment, tech stack, coding standards, shared configuration',
            ])
            ->example()
            ->phase('Backup existing Common.php')
            ->phase(
                BashTool::describe(
                    'cp ' . Runtime::NODE_DIRECTORY('Common.php') . ' ' . Runtime::NODE_DIRECTORY('Common.php.backup'),
                    'Create backup before modification'
                )
            )
            ->phase(
                ReadTool::call(Runtime::NODE_DIRECTORY('Common.php'))
            )
            ->phase(Store::as('CURRENT_COMMON_CONFIG'))
            ->phase(
                PromptMaster::call(
                    Operator::input(
                        Store::get('CURRENT_COMMON_CONFIG'),
                        Store::get('DISTRIBUTED_GUIDELINES.common'),
                        Store::get('ENVIRONMENT_CONTEXT'),
                    ),
                    Operator::task([
                        'PRESERVE existing class structure, namespace, and extends IncludeArchetype',
                        'ADD project-specific rules to handle() method from DISTRIBUTED_GUIDELINES.common',
                        'Focus on environment and universal rules:',
                        '  - Docker/container configuration awareness',
                        '  - Tech stack version constraints',
                        '  - Universal coding conventions',
                        '  - Shared infrastructure knowledge',
                        'Apply prompt engineering: clarity, brevity, token efficiency',
                        'Use $this->rule() for hard constraints, $this->guideline() for patterns',
                    ]),
                    Operator::output('{common_php_content: "...", rules_added: [...], guidelines_added: [...]}'),
                )
            )
            ->phase('Write enhanced Common.php')
            ->phase(Store::as('ENHANCED_COMMON_PHP'))
            ->phase(
                Operator::note('Common.php enhanced with shared project configuration')
            );

        // =====================================================
        // PHASE 6B: MASTER.PHP ENHANCEMENT
        // =====================================================

        $this->guideline('phase6b-master-enhancement')
            ->goal('Enhance Master.php with agent-specific rules shared by ALL Agents')
            ->note([
                'Master.php is included by AgentIncludesTrait only (NOT Brain)',
                'Rules here apply to all agents but NOT to Brain orchestration',
                'Focus: execution patterns, tool usage, task handling, code generation',
            ])
            ->example()
            ->phase('Backup existing Master.php')
            ->phase(
                BashTool::describe(
                    'cp ' . Runtime::NODE_DIRECTORY('Master.php') . ' ' . Runtime::NODE_DIRECTORY('Master.php.backup'),
                    'Create backup before modification'
                )
            )
            ->phase(
                ReadTool::call(Runtime::NODE_DIRECTORY('Master.php'))
            )
            ->phase(Store::as('CURRENT_MASTER_CONFIG'))
            ->phase(
                PromptMaster::call(
                    Operator::input(
                        Store::get('CURRENT_MASTER_CONFIG'),
                        Store::get('DISTRIBUTED_GUIDELINES.master'),
                        Store::get('ARCHITECTURE_PATTERNS'),
                    ),
                    Operator::task([
                        'PRESERVE existing class structure, namespace, and extends IncludeArchetype',
                        'ADD agent-specific rules to handle() method from DISTRIBUTED_GUIDELINES.master',
                        'Focus on agent execution patterns:',
                        '  - How agents should approach project tasks',
                        '  - Tool usage patterns for this project',
                        '  - Code generation conventions',
                        '  - Test writing patterns',
                        '  - Quality gates before task completion',
                        'Apply prompt engineering: clarity, brevity, token efficiency',
                        'Use $this->rule() for hard constraints, $this->guideline() for patterns',
                    ]),
                    Operator::output('{master_php_content: "...", rules_added: [...], guidelines_added: [...]}'),
                )
            )
            ->phase('Write enhanced Master.php')
            ->phase(Store::as('ENHANCED_MASTER_PHP'))
            ->phase(
                Operator::note('Master.php enhanced with agent-specific project configuration')
            );

        // =====================================================
        // PHASE 7: BRAIN.PHP ENHANCEMENT (PromptMaster)
        // =====================================================

        $this->guideline('phase7-brain-enhancement')
            ->goal('Enhance Brain.php with Brain-specific orchestration rules ONLY')
            ->note([
                'CRITICAL: Preserve ALL existing #[Includes()] attributes - they define the Variation',
                'ONLY add Brain-specific rules (orchestration, delegation, synthesis)',
                'Common rules go to Common.php, agent rules go to Master.php',
            ])
            ->example()
            ->phase('Backup existing Brain.php')
            ->phase(
                BashTool::describe(
                    'cp ' . Runtime::NODE_DIRECTORY('Brain.php') . ' ' . Runtime::NODE_DIRECTORY('Brain.php.backup'),
                    'Create backup before modification'
                )
            )
            ->phase('Enhance handle() method with Brain-specific content only')
            ->phase(
                PromptMaster::call(
                    Operator::input(
                        Store::get('CURRENT_BRAIN_CONFIG'),
                        Store::get('PROJECT_INCLUDES_RECOMMENDATION'),
                        Store::get('DISTRIBUTED_GUIDELINES.brain'),
                        Store::get('PROJECT_CONTEXT'),
                    ),
                    Operator::task([
                        'PRESERVE existing #[Includes()] attributes (Variation) - DO NOT MODIFY',
                        'PRESERVE existing class structure and namespace',
                        'ADD only Brain-specific rules from DISTRIBUTED_GUIDELINES.brain:',
                        '  - Orchestration and delegation strategies',
                        '  - Agent selection criteria for this project',
                        '  - Response synthesis patterns',
                        '  - Brain-level validation gates',
                        'If suggested new project includes exist, add them to #[Includes()] AFTER existing ones',
                        'Apply prompt engineering: clarity, brevity, token efficiency',
                    ]),
                    Operator::output('{brain_php_content: "...", preserved_variation: "...", changes_summary: {...}}'),
                )
            )
            ->phase('Write enhanced Brain.php')
            ->phase(Store::as('ENHANCED_BRAIN_PHP'))
            ->phase(
                Operator::note('Brain.php enhanced with Brain-specific configuration while preserving Variation')
            );

        // =====================================================
        // PHASE 8: COMPILATION AND VALIDATION
        // =====================================================

        $this->guideline('phase8-compilation')
            ->goal('Validate syntax and compile all enhanced files')
            ->example()
            ->phase('Validate PHP syntax for all modified files')
            ->phase(
                Operator::task([
                    BashTool::describe(
                        'php -l ' . Runtime::NODE_DIRECTORY('Common.php'),
                        'Validate Common.php syntax'
                    ),
                    BashTool::describe(
                        'php -l ' . Runtime::NODE_DIRECTORY('Master.php'),
                        'Validate Master.php syntax'
                    ),
                    BashTool::describe(
                        'php -l ' . Runtime::NODE_DIRECTORY('Brain.php'),
                        'Validate Brain.php syntax'
                    ),
                ])
            )
            ->phase(
                Operator::if('any syntax validation failed', [
                    'Restore all backups',
                    BashTool::call('mv ' . Runtime::NODE_DIRECTORY('Common.php.backup') . ' ' . Runtime::NODE_DIRECTORY('Common.php')),
                    BashTool::call('mv ' . Runtime::NODE_DIRECTORY('Master.php.backup') . ' ' . Runtime::NODE_DIRECTORY('Master.php')),
                    BashTool::call('mv ' . Runtime::NODE_DIRECTORY('Brain.php.backup') . ' ' . Runtime::NODE_DIRECTORY('Brain.php')),
                    'Report syntax errors',
                    Operator::output('Syntax validation failed - all backups restored'),
                ])
            )
            ->phase('Compile Brain ecosystem')
            ->phase(
                BashTool::describe(
                    BrainCLI::COMPILE,
                    ['Compile', Runtime::NODE_DIRECTORY('Brain.php'), 'with includes to', Runtime::BRAIN_FILE]
                )
            )
            ->phase(
                Operator::verify([
                    'Compilation succeeded',
                    Runtime::BRAIN_FILE . ' exists',
                    'No compilation errors',
                    'Common.php included via BrainIncludesTrait',
                    'Master.php available for AgentIncludesTrait',
                ])
            )
            ->phase(
                Operator::if('compilation failed', [
                    'Restore all backups',
                    BashTool::call('mv ' . Runtime::NODE_DIRECTORY('Common.php.backup') . ' ' . Runtime::NODE_DIRECTORY('Common.php')),
                    BashTool::call('mv ' . Runtime::NODE_DIRECTORY('Master.php.backup') . ' ' . Runtime::NODE_DIRECTORY('Master.php')),
                    BashTool::call('mv ' . Runtime::NODE_DIRECTORY('Brain.php.backup') . ' ' . Runtime::NODE_DIRECTORY('Brain.php')),
                    'Report compilation errors',
                    Operator::output('Compilation failed - all backups restored'),
                ])
            );

        // =====================================================
        // PHASE 9: KNOWLEDGE STORAGE
        // =====================================================

        $this->guideline('phase9-knowledge-storage')
            ->goal('Store all insights to vector memory for future reference')
            ->example()
            ->phase(
                VectorMemoryMcp::call('store_memory', Operator::input(
                    'content: "Brain Initialization - Project: {project_type}, Tech Stack: {tech_stack}, Patterns: {architecture_patterns}, Date: {current_date}"',
                    'category: "architecture"',
                    'tags: ["init-brain", "project-discovery", "configuration"]',
                ))
            )
            ->phase(
                VectorMemoryMcp::call('store_memory', Operator::input(
                    'content: "Environment Discovery - Docker: {has_docker}, CI/CD: {cicd_platform}, Dev Tools: {dev_tools}, Date: {current_date}"',
                    'category: "architecture"',
                    'tags: ["init-brain", "environment", "infrastructure"]',
                ))
            )
            ->phase(
                VectorMemoryMcp::call('store_memory', Operator::input(
                    'content: "Smart Distribution - Common: {common_rules_count} rules, Master: {master_rules_count} rules, Brain: {brain_rules_count} rules, Date: {current_date}"',
                    'category: "architecture"',
                    'tags: ["init-brain", "distribution", "configuration"]',
                ))
            )
            ->phase(
                VectorMemoryMcp::call('store_memory', Operator::input(
                    'content: "Best Practices Research - Frameworks: {frameworks}, Recommendations: {best_practices}, Date: {current_date}"',
                    'category: "learning"',
                    'tags: ["init-brain", "best-practices", "research"]',
                ))
            );

        // =====================================================
        // PHASE 10: REPORT GENERATION
        // =====================================================

        $this->guideline('phase10-report')
            ->goal('Generate comprehensive initialization report with smart distribution summary')
            ->example()
            ->phase(
                Operator::output([
                    'Brain Ecosystem Initialization Complete',
                    '',
                    '═══════════════════════════════════════════════════════',
                    'SMART DISTRIBUTION SUMMARY',
                    '═══════════════════════════════════════════════════════',
                    '',
                    Runtime::NODE_DIRECTORY('Common.php') . ' (Brain + ALL Agents):',
                    '  Rules Added: {common_rules_count}',
                    '  Guidelines Added: {common_guidelines_count}',
                    '  Categories: Environment, Tech Stack, Coding Standards',
                    '  Backup: ' . Runtime::NODE_DIRECTORY('Common.php.backup'),
                    '',
                    Runtime::NODE_DIRECTORY('Master.php') . ' (ALL Agents only):',
                    '  Rules Added: {master_rules_count}',
                    '  Guidelines Added: {master_guidelines_count}',
                    '  Categories: Execution Patterns, Tool Usage, Task Handling',
                    '  Backup: ' . Runtime::NODE_DIRECTORY('Master.php.backup'),
                    '',
                    Runtime::NODE_DIRECTORY('Brain.php') . ' (Brain only):',
                    '  Variation: {existing_variation_name} (PRESERVED)',
                    '  Rules Added: {brain_rules_count}',
                    '  Guidelines Added: {brain_guidelines_count}',
                    '  Categories: Orchestration, Delegation, Synthesis',
                    '  Backup: ' . Runtime::NODE_DIRECTORY('Brain.php.backup'),
                    '',
                    '═══════════════════════════════════════════════════════',
                    'DISCOVERY RESULTS',
                    '═══════════════════════════════════════════════════════',
                    '',
                    'Project:',
                    '  Type: {project_type}',
                    '  Tech Stack: {tech_stack}',
                    '  Architecture: {architecture_patterns}',
                    '',
                    'Environment:',
                    '  Docker: {has_docker}',
                    '  CI/CD Platform: {cicd_platform}',
                    '  Dev Tools: {dev_tools}',
                    '  Infrastructure: {infrastructure_services}',
                    '',
                    'Documentation:',
                    '  Files Analyzed: {docs_file_count}',
                    '  Domain Concepts: {domain_concepts_count}',
                    '  Requirements: {requirements_count}',
                    '',
                    '═══════════════════════════════════════════════════════',
                    'OUTPUT FILES',
                    '═══════════════════════════════════════════════════════',
                    '',
                    'Source Files:',
                    '  ' . Runtime::NODE_DIRECTORY('Brain.php'),
                    '  ' . Runtime::NODE_DIRECTORY('Common.php'),
                    '  ' . Runtime::NODE_DIRECTORY('Master.php'),
                    '',
                    'Compiled Output:',
                    '  ' . Runtime::BRAIN_FILE,
                    '',
                    'Backups:',
                    '  ' . Runtime::NODE_DIRECTORY('*.backup'),
                    '',
                    '═══════════════════════════════════════════════════════',
                    'VECTOR MEMORY',
                    '═══════════════════════════════════════════════════════',
                    '',
                    '  Insights Stored: {insights_count}',
                    '  Categories: architecture, learning',
                    '  Tags: init-brain, project-discovery, distribution',
                    '',
                    '═══════════════════════════════════════════════════════',
                    'NEXT STEPS',
                    '═══════════════════════════════════════════════════════',
                    '',
                    '  1. Review enhanced files:',
                    '     - Common.php: shared environment/coding rules',
                    '     - Master.php: agent execution patterns',
                    '     - Brain.php: orchestration rules (Variation preserved)',
                    '',
                    '  2. If project includes suggested:',
                    '     brain make:include {name}',
                    '',
                    '  3. Test Brain behavior with sample tasks',
                    '',
                    '  4. After any modifications:',
                    '     brain compile',
                    '',
                    '  5. Consider running:',
                    '     /init-agents for agent generation',
                    '     /init-vector for vector memory population',
                ])
            );

        // =====================================================
        // ERROR RECOVERY
        // =====================================================

        $this->guideline('error-recovery')
            ->text('Comprehensive error handling for all failure scenarios')
            ->example()
            ->phase()->if('no .docs/ found', [
                'Continue with codebase analysis only',
                'Log: Documentation not available',
            ])
            ->phase()->if('tech stack detection fails', [
                'Use manual fallback detection',
                'Analyze file extensions and structure',
            ])
            ->phase()->if('web research fails', [
                'Use cached knowledge from vector memory',
                'Continue with available information',
            ])
            ->phase()->if(BrainCLI::LIST_INCLUDES . ' fails', [
                'Use hardcoded standard includes list',
                'Log: Include discovery failed',
            ])
            ->phase()->if('Brain.php generation fails', [
                'Preserve backup',
                'Report detailed error',
                'Provide manual configuration guidance',
            ])
            ->phase()->if(BrainCLI::COMPILE . ' fails', [
                'Restore backup',
                'Analyze compilation errors',
                'Suggest fixes',
            ])
            ->phase()->if('vector memory storage fails', [
                'Continue without storage',
                'Log: Memory storage unavailable',
            ]);

        // =====================================================
        // QUALITY GATES
        // =====================================================

        $this->guideline('quality-gates')
            ->text('Validation checkpoints throughout initialization')
            ->example('Gate 1: Temporal context initialized (date, year, timestamp)')
            ->example('Gate 2: Project discovery completed with valid tech stack')
            ->example('Gate 3: Environment discovery completed (Docker, CI/CD, Dev Tools)')
            ->example('Gate 4: At least one discovery task succeeded (docs OR codebase)')
            ->example('Gate 5: Smart distribution categorization completed (Common/Master/Brain)')
            ->example('Gate 6: All backups created (Common.php.backup, Master.php.backup, Brain.php.backup)')
            ->example('Gate 7: All enhanced files pass PHP syntax validation')
            ->example('Gate 8: Compilation completes without errors')
            ->example('Gate 9: Compiled output exists at ' . Runtime::BRAIN_FILE)
            ->example('Gate 10: At least one insight stored to vector memory');

        // =====================================================
        // EXAMPLES
        // =====================================================

        $this->guideline('example-laravel-docker-project')
            ->scenario('Laravel project with Docker, Sail, and comprehensive documentation')
            ->example()
            ->phase('Discovery: Laravel 11, PHP 8.3, MySQL, Redis, Queue, Sanctum')
            ->phase('Environment: Docker (Sail), GitHub Actions CI/CD, PHPStan L8')
            ->phase('Docs: 15 .md files with architecture, requirements, domain logic')
            ->phase('Research: Laravel 2025 best practices, service container patterns')
            ->phase('')
            ->phase('SMART DISTRIBUTION:')
            ->phase('  Common.php: Docker/Sail environment rules, PHP 8.3 type constraints, MySQL conventions')
            ->phase('  Master.php: Service class patterns, repository usage, Pest test conventions')
            ->phase('  Brain.php: Agent delegation for Laravel domains (Auth, Queue, Cache)')
            ->phase('')
            ->phase('Result: All three files enhanced, Scrutinizer Variation preserved')
            ->phase('Insights: 8 architectural insights stored to vector memory');

        $this->guideline('example-node-docker-project')
            ->scenario('Node.js/Express project with Docker and TypeScript')
            ->example()
            ->phase('Discovery: Node.js 20, Express, TypeScript, MongoDB')
            ->phase('Environment: Docker Compose, GitLab CI, ESLint + Prettier')
            ->phase('Docs: None found - codebase analysis only')
            ->phase('Research: Express 2025 patterns, TypeScript best practices')
            ->phase('')
            ->phase('SMART DISTRIBUTION:')
            ->phase('  Common.php: Docker network rules, Node 20 constraints, ESLint compliance')
            ->phase('  Master.php: TypeScript type generation, async/await patterns, Jest test structure')
            ->phase('  Brain.php: API route delegation strategy')
            ->phase('')
            ->phase('Result: All three files enhanced, Architect Variation preserved')
            ->phase('Insights: 5 tech stack insights stored');

        $this->guideline('example-hybrid-microservices')
            ->scenario('Hybrid PHP/JavaScript microservices with Kubernetes')
            ->example()
            ->phase('Discovery: Laravel API + React SPA + Docker + Kafka')
            ->phase('Environment: Kubernetes, GitHub Actions, PHPStan + ESLint')
            ->phase('Docs: ADRs, API specs, deployment docs, domain model')
            ->phase('Research: Microservices patterns, event-driven architecture')
            ->phase('')
            ->phase('SMART DISTRIBUTION:')
            ->phase('  Common.php: K8s service discovery, cross-service authentication, Kafka topic naming')
            ->phase('  Master.php: Event schema validation, API contract testing, service boundary respect')
            ->phase('  Brain.php: Multi-service orchestration, cross-domain delegation, event saga coordination')
            ->phase('')
            ->phase('Project Includes: Suggested MicroserviceBoundaries.php, EventSchemas.php')
            ->phase('Result: All three files enhanced with microservice awareness')
            ->phase('Insights: 12 cross-cutting concerns stored');

        // =====================================================
        // PERFORMANCE OPTIMIZATION
        // =====================================================

        $this->guideline('performance-optimization')
            ->text('Optimization strategies for efficient initialization')
            ->example()
            ->phase('Parallel Execution: All independent tasks run simultaneously')
            ->phase('Selective Reading: Only read files needed for analysis')
            ->phase('Incremental Storage: Store insights progressively, not at end')
            ->phase('Smart Caching: Leverage vector memory for repeated runs')
            ->phase('Early Validation: Fail fast on critical errors')
            ->phase('Streaming Output: Report progress as phases complete');

        // =====================================================
        // DIRECTIVE
        // =====================================================

        $this->guideline('directive')
            ->text('Core initialization directive')
            ->example('Discover thoroughly! Research current practices! Configure precisely! Validate rigorously! Store knowledge! Report comprehensively!');
    }
}
