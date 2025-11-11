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
            ->text('Always use compilation variables ({{ VARIABLE }}) instead of hardcoded paths like .claude/ or .brain/node/.')
            ->why('Ensures platform-agnostic code that works across all compilation targets.')
            ->onViolation('Replace hardcoded paths with proper {{ VARIABLE }} references.');

        $this->rule('respect-archetype-structure')->critical()
            ->text('Each archetype type has specific structure requirements: attributes, extends clause, handle() method.')
            ->why('Ensures proper compilation and prevents structural errors.')
            ->onViolation('Review archetype type requirements and align structure accordingly.');

        $this->rule('use-includes-not-duplication')->high()
            ->text('Never duplicate logic across archetypes. Extract to Include and reference via #[Includes()] attribute.')
            ->why('Maintains DRY principle and ensures single source of truth.')
            ->onViolation('Create Include for shared logic and remove duplication.');

        $this->guideline('directive')
            ->text('Core directive for compilation system knowledge.')
            ->example('Platform-agnostic: Use {{ VARIABLES }} everywhere')
            ->example('Structure-first: Follow archetype templates exactly')
            ->example('DRY: Extract shared logic to Includes')
            ->example('Validate: Compile after changes to verify correctness');
    }
}
