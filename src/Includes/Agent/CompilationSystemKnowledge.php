<?php

declare(strict_types=1);

namespace BrainCore\Includes\Agent;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose('Deep knowledge of Brain compilation system architecture, Builder API syntax, archetype types, and multi-target support. Essential for agents creating or modifying Brain components.')]
class CompilationSystemKnowledge extends IncludeArchetype
{
    protected function handle(): void
    {
        $this->guideline('compilation-flow')
            ->text('Understanding of full compilation pipeline.')
            ->example('{{ NODE_DIRECTORY }}/*.php → brain compile → brain-core get:file --xml/json/yaml/toml → {{ BRAIN_FOLDER }} + {{ AGENTS_FOLDER }} + {{ COMMANDS_FOLDER }} + {{ MCP_FILE }}')->key('flow');

        $this->guideline('source-vs-compiled-directories')
            ->text('Clear separation between source (editable) and compiled (readonly) directories.')
            ->example('SOURCE (EDITABLE): {{ NODE_DIRECTORY }} - All PHP classes (Brain.php, Agents/*.php, Commands/*.php, Skills/*.php, Includes/*.php, Mcp/*.php)')->key('source')
            ->example('COMPILED (READONLY): {{ BRAIN_FOLDER }} - Auto-generated output from compilation (CLAUDE.md, agents/*.md, commands/*.md, skills/*.md)')->key('compiled')
            ->example('{{ BRAIN_FOLDER }} = {{ BRAIN_FILE }} parent directory')->key('brain-folder')
            ->example('{{ AGENTS_FOLDER }} = {{ BRAIN_FOLDER }}/agents/')->key('agents-folder')
            ->example('{{ COMMANDS_FOLDER }} = {{ BRAIN_FOLDER }}/commands/')->key('commands-folder')
            ->example('{{ SKILLS_FOLDER }} = {{ BRAIN_FOLDER }}/skills/')->key('skills-folder')
            ->example('Workflow: Edit {{ NODE_DIRECTORY }}/*.php → brain compile → Auto-generates {{ BRAIN_FOLDER }}/*')->key('workflow')
            ->example('NEVER Write/Edit to {{ BRAIN_FOLDER }}, {{ AGENTS_FOLDER }}, {{ COMMANDS_FOLDER }}, {{ SKILLS_FOLDER }} - these are compilation artifacts')->key('never-edit-compiled');

        $this->guideline('builder-api-rules')
            ->text('Core Builder API syntax patterns.')
            ->example('$this->rule(id)->severity()->text()->why()->onViolation()')->key('rules')
            ->example('$this->guideline(id)->text()->example()')->key('guidelines')
            ->example('$this->guideline(id)->example()->phase(id, text)')->key('phases')
            ->example('$this->guideline(id)->example(value)->key(name)')->key('key-value')
            ->example('$this->style()->language()->tone()->brevity()')->key('style')
            ->example('$this->response()->sections()->section(name, brief, required)')->key('response')
            ->example('$this->determinism()->ordering()->randomness()')->key('determinism');

        $this->guideline('archetype-types')
            ->text('Six archetype types with distinct purposes and outputs.')
            ->example('Brain: Main orchestrator → {{ BRAIN_FILE }}')->key('brain')
            ->example('Agents: Specialized execution → {{ AGENTS_FOLDER }}/{name}.md')->key('agents')
            ->example('Skills: Reusable modules → {{ SKILLS_FOLDER }}/{name}.md')->key('skills')
            ->example('Commands: User slash commands → {{ COMMANDS_FOLDER }}/{name}.md')->key('commands')
            ->example('Includes: Compile-time fragments → NO output (merged)')->key('includes')
            ->example('Mcp: MCP server configs → {{ MCP_FILE }}')->key('mcp');

        $this->guideline('archetype-attributes')
            ->text('Required attributes for each archetype type.')
            ->example('Brain: #[Meta(id, brain-id)] #[Purpose()] extends BrainArchetype')->key('brain-attrs')
            ->example('Agents: #[Meta(id, agent-id)] #[Meta(model)] #[Meta(color)] #[Meta(description)] #[Purpose()] #[Includes()] extends AgentArchetype')->key('agent-attrs')
            ->example('Commands: #[Meta(id, cmd-id)] #[Meta(description)] #[Purpose()] extends CommandArchetype')->key('command-attrs')
            ->example('Includes: #[Purpose()] extends IncludeArchetype')->key('include-attrs');

        $this->guideline('include-system')
            ->text('Compile-time include merging mechanics.')
            ->example('Includes merge during compilation, disappear at runtime')->key('compile-time')
            ->example('Source includes → Merger flattens → Builder outputs → Compiled (no include references)')->key('flow')
            ->example('Recursive includes up to 255 levels supported')->key('recursive')
            ->example('DRY: Change once → recompile → all targets updated')->key('dry')
            ->example('Use #[Includes(ClassName::class)] attributes, not $this->include()')->key('syntax');

        $this->guideline('multi-target-support')
            ->text('Compilation targets and their output formats.')
            ->example('claude → XmlBuilder → {{ BRAIN_FOLDER }}/CLAUDE.md')->key('claude')
            ->example('codex → JsonBuilder → .codex/CODEX.json')->key('codex')
            ->example('qwen → YamlBuilder → .qwen/QWEN.yaml')->key('qwen')
            ->example('gemini → JsonBuilder → .gemini/GEMINI.json')->key('gemini')
            ->example('Command: brain compile [target]')->key('command');

        $this->guideline('compilation-variables')
            ->text('Platform-agnostic variables for cross-target compatibility.')
            ->example('{{ PROJECT_DIRECTORY }} - Root path')->key('project')
            ->example('{{ BRAIN_DIRECTORY }} - Brain dir (.brain/)')->key('brain-dir')
            ->example('{{ NODE_DIRECTORY }} - Source dir (.brain/node/)')->key('node-dir')
            ->example('{{ BRAIN_FILE }} - Compiled brain file')->key('brain-file')
            ->example('{{ BRAIN_FOLDER }} - Compiled brain folder')->key('brain-folder')
            ->example('{{ AGENTS_FOLDER }} - Compiled agents folder')->key('agents-folder')
            ->example('{{ COMMANDS_FOLDER }} - Compiled commands folder')->key('commands-folder')
            ->example('{{ SKILLS_FOLDER }} - Compiled skills folder')->key('skills-folder')
            ->example('{{ MCP_FILE }} - MCP config file')->key('mcp-file')
            ->example('{{ AGENT }} - Current target (claude/codex/qwen/gemini)')->key('agent-target')
            ->example('{{ DATE }}, {{ YEAR }}, {{ TIMESTAMP }} - Temporal variables')->key('temporal');

        $this->guideline('brain-includes')
            ->text('Standard Brain includes organized by category.')
            ->example('Brain-specific: BrainCore, PreActionValidation, AgentDelegation, AgentResponseValidation, CognitiveArchitecture, CollectiveIntelligencePhilosophy, CompactionRecovery, ContextAnalysis, CorrectionProtocolEnforcement, DelegationProtocols, EdgeCases')->key('brain-specific')
            ->example('Universal: AgentLifecycleFramework, CoreConstraints, ErrorRecovery, InstructionWritingStandards, LaravelBoostGuidelines, QualityGates, ResponseFormatting, SequentialReasoningCapability, VectorMasterStorageStrategy')->key('universal')
            ->example('Agent core: AgentIdentity, ToolsOnlyExecution, TemporalContextAwareness, AgentVectorMemory')->key('agent-core')
            ->example('Policies: SkillsUsagePolicy, DocumentationFirstPolicy')->key('policies')
            ->example('Specialized: WebRecursiveResearch, WebBasicResearch, GitConventionalCommits, GithubHierarchy, ArchitectLifecycle, ArchitectTemplateSystem')->key('specialized');

        $this->guideline('output-format-rules')
            ->text('XML/JSON/YAML output formatting requirements.')
            ->example('XML: No tabs/indentation (newlines only), double newlines between top-level blocks')->key('xml')
            ->example('Self-closing empty tags in XML')->key('self-close')
            ->example('Escaped content in all formats')->key('escape')
            ->example('Enum → scalar values')->key('enum')
            ->example('Stable ordering: purpose → iron_rules → guidelines → style → response_contract → determinism')->key('ordering');

        $this->guideline('cli-commands')
            ->text('Brain CLI commands for development workflow.')
            ->example('brain compile [target] - Compile to target format')->key('compile')
            ->example('brain init - Initialize Brain project')->key('init')
            ->example('brain master:list - List of agents')->key('list-master')
            ->example('brain includes:list - List of includes')->key('list-includes')
            ->example('brain make:master Name - Create agent')->key('make-master')
            ->example('brain make:skill Name - Create skill')->key('make-skill')
            ->example('brain make:command Name - Create command')->key('make-command')
            ->example('brain make:include Name - Create include')->key('make-include')
            ->example('brain make:mcp Name - Create MCP config')->key('make-mcp')
            ->example('brain list - List available commands')->key('list')
            ->example('brain-core get:file file.php --xml/json/yaml/toml - Low-level compilation')->key('core');

        $this->guideline('memory-architecture')
            ->text('Vector memory access rules and topology.')
            ->example('Location: ./memory/ (SQLite)')->key('location')
            ->example('Access: MCP-only (NEVER direct file access)')->key('access')
            ->example('Tools: store_memory, search_memories, list_recent_memories, get_by_memory_id, delete_by_memory_id, get_memory_stats, clear_old_memories')->key('tools')
            ->example('Topology: Master (Brain exclusive write), Replica (Agents read-only cached)')->key('topology')
            ->example('Sync: Async every 5min, consistency ≤10min')->key('sync')
            ->example('Categories: code-solution, bug-fix, architecture, learning, tool-usage, debugging, performance, security, other')->key('categories');

        $this->rule('never-hardcode-paths')->critical()
            ->text('ABSOLUTELY FORBIDDEN TO HARDCODE PATHS. ALWAYS USE COMPILATION VARIABLES. EVERY path reference MUST use \{\{ VARIABLE \}\} syntax. NO EXCEPTIONS EVER.')
            ->why('Hardcoded paths break multi-target compilation (claude/codex/qwen/gemini), prevent platform portability, and violate single-source-of-truth principle. Variables ensure all targets compile correctly.')
            ->onViolation('STOP IMMEDIATELY. Replace ALL hardcoded paths with \{\{ VARIABLE \}\}. Scan entire output for hardcoded paths before submitting. ZERO TOLERANCE.');

        $this->guideline('path-variables-examples')
            ->text('Concrete examples of FORBIDDEN vs CORRECT path usage.')
            ->example('FORBIDDEN: "{{ BRAIN_FILE }}" → CORRECT: "\{\{ BRAIN_FILE \}\}"')->key('brain-file')
            ->example('FORBIDDEN: "{{ BRAIN_FOLDER }}" → CORRECT: "\{\{ BRAIN_FOLDER \}\}"')->key('brain-folder')
            ->example('FORBIDDEN: "{{ NODE_DIRECTORY }}" → CORRECT: "\{\{ NODE_DIRECTORY \}\}"')->key('node-dir')
            ->example('FORBIDDEN: "{{ NODE_DIRECTORY }}Brain.php" → CORRECT: "\{\{ NODE_DIRECTORY \}\}Brain.php"')->key('node-brain')
            ->example('FORBIDDEN: "{{ AGENTS_FOLDER }}" → CORRECT: "\{\{ AGENTS_FOLDER \}\}"')->key('agents')
            ->example('FORBIDDEN: "{{ COMMANDS_FOLDER }}" → CORRECT: "\{\{ COMMANDS_FOLDER \}\}"')->key('commands')
            ->example('FORBIDDEN: "{{ SKILLS_FOLDER }}" → CORRECT: "\{\{ SKILLS_FOLDER \}\}"')->key('skills')
            ->example('FORBIDDEN: "{{ MCP_FILE }}" → CORRECT: "\{\{ MCP_FILE \}\}"')->key('mcp')
            ->example('FORBIDDEN: "{{ BRAIN_DIRECTORY }}" → CORRECT: "\{\{ BRAIN_DIRECTORY \}\}"')->key('brain-directory')
            ->example('RULE: If you see literal path string ({{ BRAIN_FOLDER }}, {{ NODE_DIRECTORY }}, etc. after compilation) → you violated this rule. Always write \{\{ VARIABLE \}\} in source code.')->key('rule');

        $this->rule('never-write-to-compiled')->critical()
            ->text('ABSOLUTELY FORBIDDEN: Write(), Edit(), or ANY file operations to {{ BRAIN_FOLDER }}, {{ BRAIN_FILE }}, {{ AGENTS_FOLDER }}, {{ COMMANDS_FOLDER }}, {{ SKILLS_FOLDER }} paths. These directories contain COMPILED OUTPUT (readonly, auto-generated). ONLY ALLOWED: Write/Edit to {{ NODE_DIRECTORY }} PHP source files (Brain.php, Agents/*.php, Commands/*.php, Skills/*.php, Includes/*.php). MANDATORY workflow: Use brain make:command/make:master/make:skill → Edit {{ NODE_DIRECTORY }}/*.php → brain compile → auto-generates {{ BRAIN_FOLDER }}/*. NO EXCEPTIONS.')
            ->why('{{ BRAIN_FOLDER }} is compilation artifact auto-generated from {{ NODE_DIRECTORY }} sources. Direct edits bypass compilation system, corrupt architecture, and are overwritten on next compile. Single source of truth is {{ NODE_DIRECTORY }} PHP classes.')
            ->onViolation('ABORT ALL OPERATIONS IMMEDIATELY. DO NOT WRITE. DO NOT EDIT. STEP 1: Determine task type (command/agent/skill). STEP 2: Execute appropriate brain make:* command. STEP 3: Edit ONLY {{ NODE_DIRECTORY }}/*.php source. STEP 4: Run brain compile. VIOLATION = CRITICAL ARCHITECTURE CORRUPTION.');

        $this->guideline('pre-write-validation-checklist')
            ->text('MANDATORY checklist executed BEFORE any Write() or Edit() operation.')
            ->example()
                ->phase('check-1', 'Verify target path starts with {{ NODE_DIRECTORY }} (e.g., {{ NODE_DIRECTORY }}/Commands/FooCommand.php)')
                ->phase('check-2', 'Verify target path ends with .php extension')
                ->phase('check-3', 'Verify target path does NOT contain {{ BRAIN_FOLDER }}, {{ AGENTS_FOLDER }}, {{ COMMANDS_FOLDER }}, {{ SKILLS_FOLDER }}')
                ->phase('check-4', 'If creating new file: verify brain make:* command executed first')
                ->phase('check-5', 'If ANY check fails: ABORT operation and use correct workflow')
                ->phase('validation', 'ALL checks MUST pass before Write/Edit execution');

        $this->guideline('file-creation-decision-tree')
            ->text('Decision tree for creating Brain components.')
            ->example('Task: Create command → Action: Bash("brain make:command CommandName") → Edit({{ NODE_DIRECTORY }}/Commands/CommandNameCommand.php) → Bash("brain compile")')->key('command')
            ->example('Task: Create agent → Action: Bash("brain make:master AgentName") → Edit({{ NODE_DIRECTORY }}/Agents/AgentNameMaster.php) → Bash("brain compile")')->key('agent')
            ->example('Task: Create skill → Action: Bash("brain make:skill SkillName") → Edit({{ NODE_DIRECTORY }}/Skills/SkillNameSkill.php) → Bash("brain compile")')->key('skill')
            ->example('Task: Create include → Action: Bash("brain make:include IncludeName") → Edit({{ NODE_DIRECTORY }}/Includes/IncludeName.php) → Bash("brain compile")')->key('include')
            ->example('Task: Edit existing → Action: Read({{ NODE_DIRECTORY }}/*.php) → Edit({{ NODE_DIRECTORY }}/*.php) → Bash("brain compile")')->key('edit')
            ->example('FORBIDDEN: Write({{ BRAIN_FOLDER }}/*) or Write({{ COMMANDS_FOLDER }}/*) or Write({{ AGENTS_FOLDER }}/*)')->key('forbidden');

        $this->rule('respect-archetype-structure')->critical()
            ->text('Each archetype type has specific structure requirements: attributes, extends clause, handle() method.')
            ->why('Ensures proper compilation and prevents structural errors.')
            ->onViolation('Review archetype type requirements and align structure accordingly.');

        $this->rule('use-includes-not-duplication')->high()
            ->text('Never duplicate logic across archetypes. Extract to Include and reference via #[Includes()] attribute.')
            ->why('Maintains DRY principle and ensures single source of truth.')
            ->onViolation('Create Include for shared logic and remove duplication.');

        $this->rule('use-php-api-not-strings')->critical()
            ->text('ABSOLUTELY FORBIDDEN TO WRITE PSEUDO-SYNTAX AS STRINGS. ALWAYS USE PHP API FROM BrainCore\\Compilation NAMESPACE. EVERY workflow, operator, tool call MUST use PHP static methods. NO STRING PSEUDO-SYNTAX IN SOURCE CODE. ZERO EXCEPTIONS.')
            ->why('PHP API ensures: (1) Single source of truth for syntax changes, (2) Type safety and IDE support, (3) Consistent compilation across all targets, (4) Ability to evolve pseudo-syntax format without changing every guideline manually.')
            ->onViolation('STOP IMMEDIATELY. Replace ALL string pseudo-syntax with PHP API calls. Scan entire handle() method for string violations before submitting. ZERO TOLERANCE.');

        $this->guideline('php-api-complete-reference')
            ->text('Complete PHP API for pseudo-syntax generation from BrainCore\\Compilation namespace.')
            ->example('BashTool::call(\'command\') - Generate Bash(\'command\')')->key('bash-tool')
            ->example('ReadTool::call(Runtime::NODE_DIRECTORY(\'path\')) - Generate Read(\'{{ NODE_DIRECTORY }}path\')')->key('read-tool')
            ->example('TaskTool::agent(\'name\', \'task\') - Generate Task(@agent-name, \'task\')')->key('task-tool')
            ->example('WebSearchTool::describe(\'query\') - Generate WebSearch(\'query\')')->key('web-search-tool')
            ->example('Store::as(\'VAR\', \'value\') - Generate STORE-AS($VAR = \'value\')')->key('store-as')
            ->example('Store::get(\'VAR\') - Generate STORE-GET($VAR)')->key('store-get')
            ->example('Operator::task([...]) - Generate TASK → [...] → END-TASK')->key('operator-task')
            ->example('Operator::if(\'cond\', [\'then\'], [\'else\']) - Generate IF(cond) → THEN → [...] → ELSE → [...] → END-IF')->key('operator-if')
            ->example('Operator::forEach(\'item\', [...]) - Generate FOREACH(item) → [...] → END-FOREACH')->key('operator-foreach')
            ->example('Operator::verify(...) - Generate VERIFY-SUCCESS(...)')->key('operator-verify')
            ->example('Operator::report(...) - Generate REPORT(...)')->key('operator-report')
            ->example('Operator::skip(...) - Generate SKIP(...)')->key('operator-skip')
            ->example('Operator::note(...) - Generate NOTE(...)')->key('operator-note')
            ->example('Operator::context(...) - Generate CONTEXT(...)')->key('operator-context')
            ->example('Operator::output(...) - Generate OUTPUT(...)')->key('operator-output')
            ->example('Operator::input(...) - Generate INPUT(...)')->key('operator-input')
            ->example('Runtime::BRAIN_FILE - Generate {{ BRAIN_FILE }}')->key('runtime-brain-file')
            ->example('Runtime::NODE_DIRECTORY(\'path\') - Generate {{ NODE_DIRECTORY }}path')->key('runtime-node-directory')
            ->example('Runtime::AGENTS_FOLDER - Generate {{ AGENTS_FOLDER }}')->key('runtime-agents-folder')
            ->example('BrainCLI::COMPILE - Generate \'brain compile\'')->key('brain-cli-compile')
            ->example('BrainCLI::MAKE_MASTER(\'Name\') - Generate \'brain make:master Name\'')->key('brain-cli-make-master')
            ->example('BrainCLI::MASTER_LIST - Generate \'brain master:list\'')->key('brain-cli-master-list')
            ->example('ExploreMaster::call(...) - Generate Task(@agent-explore-master, ...)')->key('explore-master')
            ->example('AgentMaster::call(...) - Generate Task(@agent-agent-master, ...)')->key('agent-master')
            ->example('VectorMemoryMcp::call(\'store_memory\', \'{...}\') - Generate mcp__vector-memory__store_memory(\'{...}\')')->key('vector-memory-mcp');

        $this->guideline('php-api-usage-patterns')
            ->text('Common PHP API usage patterns in handle() method.')
            ->example()->phase('pattern-1', '$this->guideline(\'id\')->example(BashTool::call(BrainCLI::COMPILE));')
            ->phase('pattern-2', '$this->guideline(\'id\')->example(Store::as(\'VAR\', \'initial value\'));')
            ->phase('pattern-3', '$this->guideline(\'id\')->example(Operator::task([ReadTool::call(Runtime::NODE_DIRECTORY()), BashTool::call(\'ls\')]));')
            ->phase('pattern-4', '$this->guideline(\'id\')->example(Operator::if(\'condition\', [\'action-true\'], [\'action-false\']));')
            ->phase('pattern-5', '$this->guideline(\'id\')->example(TaskTool::agent(\'explore-master\', \'Scan project structure\'));');

        $this->guideline('forbidden-vs-correct-examples')
            ->text('Concrete examples of FORBIDDEN string syntax vs CORRECT PHP API usage.')
            ->example('FORBIDDEN: ->example(\'Bash("brain compile")\') → CORRECT: ->example(BashTool::call(BrainCLI::COMPILE))')->key('bash')
            ->example('FORBIDDEN: ->example(\'Read("{{ NODE_DIRECTORY }}Brain.php")\') → CORRECT: ->example(ReadTool::call(Runtime::NODE_DIRECTORY(\'Brain.php\')))')->key('read')
            ->example('FORBIDDEN: ->example(\'Task(@agent-explore-master, "task")\') → CORRECT: ->example(TaskTool::agent(\'explore-master\', \'task\'))')->key('task')
            ->example('FORBIDDEN: ->example(\'STORE-AS($VAR)\') → CORRECT: ->example(Store::as(\'VAR\'))')->key('store')
            ->example('FORBIDDEN: ->example(\'IF(cond) → THEN → [...]\') → CORRECT: ->example(Operator::if(\'cond\', [\'then\']))')->key('operator-if')
            ->example('FORBIDDEN: ->example(\'FOREACH(item) → [...]\') → CORRECT: ->example(Operator::forEach(\'item\', [...]))')->key('operator-foreach')
            ->example('FORBIDDEN: ->example(\'{{ BRAIN_FILE }}\') → CORRECT: ->example(Runtime::BRAIN_FILE)')->key('runtime')
            ->example('FORBIDDEN: ->example(\'brain make:master Foo\') → CORRECT: ->example(BrainCLI::MAKE_MASTER(\'Foo\'))')->key('cli')
            ->example('RULE: If you see literal pseudo-syntax strings (\'Bash(...)\', \'Task(...)\', \'STORE-AS(...)\') in ->example() → you violated this rule. Always use PHP API.')->key('rule');

        $this->rule('scan-actual-syntax-files')->critical()
            ->text('MANDATORY: Before generating ANY command/agent code, MUST Read actual PHP syntax files to understand current API. PHP API evolves - documentation examples may be outdated. ALWAYS verify against SOURCE CODE.')
            ->why('PHP API is single source of truth. Syntax can evolve (new methods, changed signatures, new helpers). Reading actual classes ensures correct, up-to-date syntax usage. Prevents outdated pseudo-syntax patterns.')
            ->onViolation('STOP. Read required syntax files BEFORE code generation. Use Glob + Read to scan directories and understand actual API.');

        $this->guideline('mandatory-syntax-scanning-workflow')
            ->text('MANDATORY workflow for understanding actual PHP API syntax.')
            ->example()
                ->phase('step-1', 'Glob("{{ BRAIN_DIRECTORY }}vendor/jarvis-brain/core/src/Compilation/**/*.php") → Get list of ALL compilation helper classes')
                ->phase('step-2', 'Read each Compilation/*.php file (Operator.php, Store.php, Runtime.php, BrainCLI.php, Tools/*.php) → Extract available methods and signatures')
                ->phase('step-3', 'Glob("{{ NODE_DIRECTORY }}Mcp/*.php") → Get list of MCP classes')
                ->phase('step-4', 'Read MCP classes → Understand ::call(string $name, ...$args) and ::id(...$args) patterns')
                ->phase('step-5', 'Glob("{{ NODE_DIRECTORY }}Commands/*.php") → Get list of Command classes (optional - can use brain master:list for agent names)')
                ->phase('step-6', 'Read Command classes → Understand ::id(...$args) pattern for command references')
                ->phase('validation', 'ALL syntax files scanned → Build complete PHP API map → Generate code using ACTUAL methods from source');

        $this->guideline('syntax-files-to-scan')
            ->text('Specific directories and files to scan for actual PHP API syntax.')
            ->example('{{ BRAIN_DIRECTORY }}vendor/jarvis-brain/core/src/Compilation/ - MANDATORY: ALL *.php files (Tools/BashTool.php, Tools/TaskTool.php, Tools/ReadTool.php, Tools/WebSearchTool.php, Tools/EditTool.php, Operator.php, Store.php, Runtime.php, BrainCLI.php, Traits/CompileStandartsTrait.php)')->key('compilation-all')
            ->example('{{ NODE_DIRECTORY }}Mcp/*.php - MANDATORY for MCP usage: All MCP classes (VectorMemoryMcp.php, etc.) → Extract ::call() and ::id() methods')->key('mcp-classes')
            ->example('{{ NODE_DIRECTORY }}Commands/*.php - OPTIONAL: Command classes have ::id() method for referencing, but brain master:list sufficient for agent names')->key('command-classes')
            ->example('{{ NODE_DIRECTORY }}Agents/*.php - SKIP: Agent files not needed, use brain master:list for agent discovery')->key('agent-skip')
            ->example('{{ BRAIN_DIRECTORY }}vendor/jarvis-brain/core/src/Architectures/ToolArchitecture.php - MANDATORY: Base class for all tools, provides ::call() and ::describe() methods')->key('tool-architecture');

        $this->guideline('syntax-extraction-examples')
            ->text('What to extract from syntax files.')
            ->example('From Operator.php: Static method signatures (if(), forEach(), task(), verify(), report(), skip(), note(), context(), output(), input(), validate(), delegate())')->key('operator-methods')
            ->example('From Store.php: Static methods (as($name, ...$appropriate), get($name))')->key('store-methods')
            ->example('From Runtime.php: Constants (BRAIN_FILE, NODE_DIRECTORY, etc.) + Static methods (NODE_DIRECTORY(...$append), BRAIN_FOLDER(...$append), etc.)')->key('runtime-methods')
            ->example('From BrainCLI.php: Constants (COMPILE, MAKE_MASTER, etc.) + Static methods (MAKE_MASTER(...$args), DOCS(...$args), etc.)')->key('cli-methods')
            ->example('From Tools/*.php: Tool classes extending ToolArchitecture → Each has ::call(), ::describe(), some have custom methods like TaskTool::agent()')->key('tool-methods')
            ->example('From Mcp/*.php: MCP classes → ::call(string $name, ...$args) for mcp__{id}__{name}(...) and ::id(...$args) for referencing')->key('mcp-methods')
            ->example('From ToolArchitecture.php: Base methods available to ALL tools (call(...$parameters), describe(string|array $command, ...$steps))')->key('base-methods');

        $this->guideline('when-to-scan-syntax')
            ->text('Situations requiring mandatory syntax file scanning.')
            ->example('Before creating new Command - scan Compilation + Mcp classes')->key('create-command')
            ->example('Before creating new Agent - scan Compilation + Mcp classes')->key('create-agent')
            ->example('Before creating new Skill - scan Compilation classes')->key('create-skill')
            ->example('Before modifying existing Command/Agent/Skill with new pseudo-syntax - re-scan to verify API changes')->key('modify-existing')
            ->example('When user reports syntax errors - scan to verify correct current API')->key('debug-syntax')
            ->example('After Brain system upgrade - re-scan to discover new API methods')->key('after-upgrade');

        $this->guideline('command-includes-policy')
            ->text('Commands DO NOT include Universal includes (CoreConstraints, QualityGates, etc.) - they are already in Brain.')
            ->example('Commands: NO #[Includes()] attribute - commands inherit context from Brain')->key('commands-no-includes')
            ->example('Agents: YES #[Includes(CoreConstraints, QualityGates, ...)] - agents need explicit context')->key('agents-need-includes')
            ->example('Brain: YES #[Includes(Universal, Brain-specific)] - Brain loads everything')->key('brain-loads-all')
            ->example('Reason: Commands execute in Brain context, inheriting all Brain includes automatically')->key('reason')
            ->example('FORBIDDEN for Commands: #[Includes(CoreConstraints::class)], #[Includes(QualityGates::class)]')->key('forbidden')
            ->example('ALLOWED for Commands: ONLY command-specific custom includes if absolutely necessary')->key('allowed');

        $this->guideline('agent-vs-command-structure')
            ->text('Structural differences between Agents and Commands.')
            ->example('Agent: #[Meta(id)], #[Meta(model)], #[Meta(color)], #[Meta(description)], #[Purpose()], #[Includes(...many...)], extends AgentArchetype')->key('agent-structure')
            ->example('Command: #[Meta(id)], #[Meta(description)], #[Purpose()], extends CommandArchetype (NO #[Includes()])')->key('command-structure')
            ->example('Agent: Self-contained execution context, needs all includes explicitly declared')->key('agent-context')
            ->example('Command: Executes within Brain context, inherits Brain includes automatically')->key('command-context');

        $this->rule('commands-no-universal-includes')->critical()
            ->text('Commands MUST NOT include Universal includes (CoreConstraints, QualityGates, ErrorRecovery, etc.). Commands execute in Brain context and inherit these automatically.')
            ->why('Commands run within Brain\'s execution context. Universal includes are already loaded by Brain. Including them in commands creates duplication, bloats compilation, and violates single-source-of-truth principle.')
            ->onViolation('Remove ALL Universal includes from Command #[Includes()]. Commands should have MINIMAL or NO includes. Only add command-specific custom includes if absolutely necessary.');

        $this->guideline('directive')
            ->text('Core directive for compilation system knowledge.')
            ->example('PHP-first: Use PHP API, never string pseudo-syntax')
            ->example('Platform-agnostic: Use {{ VARIABLES }} everywhere')
            ->example('Structure-first: Follow archetype templates exactly')
            ->example('DRY: Extract shared logic to Includes')
            ->example('Commands-minimal: No Universal includes in commands')
            ->example('Validate: Compile after changes to verify correctness');
    }
}
