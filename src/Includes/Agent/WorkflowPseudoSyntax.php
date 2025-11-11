<?php

declare(strict_types=1);

namespace BrainCore\Includes\Agent;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose('Workflow pseudo-syntax knowledge for expressing complex workflows as structured data. Compiles PHP Builder API to human-readable instructions in compiled output.')]
class WorkflowPseudoSyntax extends IncludeArchetype
{
    protected function handle(): void
    {
        $this->guideline('workflow-syntax-overview')
            ->text('Declarative language embedded in guidelines for procedural instructions, control flow, and tool invocations.')
            ->example('BashTool::call(cmd) → Bash(cmd)')->key('bash')
            ->example('ReadTool::call(path) → Read(path)')->key('read')
            ->example('TaskTool::agent(name, task) → Task(@agent-name task)')->key('task')
            ->example('WebSearchTool::describe(query) → WebSearch(query)')->key('websearch')
            ->example('Store::as(VAR) → STORE-AS($VAR)')->key('store')
            ->example('Store::get(VAR) → STORE-GET($VAR)')->key('get')
            ->example('Operator::task([...]) → TASK → [...] → END-TASK')->key('task-block')
            ->example('Operator::if(cond, then, else) → IF(cond) → THEN → [...] → ELSE → [...] → END-IF')->key('if')
            ->example('Operator::forEach(item, [...]) → FOREACH(item) → [...] → END-FOREACH')->key('foreach');

        $this->guideline('workflow-operators')
            ->text('Control flow operators for complex logic.')
            ->example('Operator::skip(reason) → SKIP(reason)')->key('skip')
            ->example('Operator::report(msg) → REPORT(msg)')->key('report')
            ->example('Operator::verify(...) → VERIFY-SUCCESS(...)')->key('verify')
            ->example('Operator::output(format) → OUTPUT(format)')->key('output')
            ->example('Operator::input(...) → INPUT(...)')->key('input')
            ->example('Operator::context(data) → CONTEXT(data)')->key('context')
            ->example('Operator::note(text) → NOTE(text)')->key('note')
            ->example('Operator::do(...) → Actions with → separators')->key('do');

        $this->guideline('workflow-runtime-constants')
            ->text('Platform-agnostic runtime constants for paths.')
            ->example('Runtime::BRAIN_FILE → {{ BRAIN_FILE }}')->key('brain-file')
            ->example('Runtime::NODE_DIRECTORY(path) → {{ NODE_DIRECTORY }}/path')->key('node-dir')
            ->example('Runtime::AGENTS_FOLDER → {{ AGENTS_FOLDER }}')->key('agents-folder')
            ->example('Runtime::BRAIN_FOLDER → {{ BRAIN_FOLDER }}')->key('brain-folder')
            ->example('BrainCLI::COMPILE → brain compile')->key('compile')
            ->example('BrainCLI::MAKE_MASTER(Name) → brain make:master Name')->key('make-master')
            ->example('BrainCLI::MASTER_LIST → brain master:list')->key('master-list');

        $this->guideline('workflow-agent-delegation')
            ->text('Agent delegation syntax for Task tool invocations.')
            ->example('ExploreMaster::call(...) → Task(@agent-explore, ...)')->key('explore')
            ->example('AgentMaster::call(...) → Task(@agent-agent-master, ...)')->key('agent')
            ->example('WebResearchMaster::call(...) → Task(@agent-web-research-master, ...)')->key('web')
            ->example('CommitMaster::call(...) → Task(@agent-commit-master, ...)')->key('commit')
            ->example('PmMaster::call(...) → Task(@agent-pm-master, ...)')->key('pm')
            ->example('PromptMaster::call(...) → Task(@agent-prompt-master, ...)')->key('prompt');

        $this->guideline('workflow-mcp-tools')
            ->text('MCP tool call syntax for vector memory operations.')
            ->example('VectorMemoryMcp::call(method, args) → mcp__vector-memory__method(args)')->key('mcp')
            ->example('store_memory → mcp__vector-memory__store_memory')->key('store')
            ->example('search_memories → mcp__vector-memory__search_memories')->key('search')
            ->example('list_recent_memories → mcp__vector-memory__list_recent_memories')->key('list')
            ->example('get_by_memory_id → mcp__vector-memory__get_by_memory_id')->key('get')
            ->example('delete_by_memory_id → mcp__vector-memory__delete_by_memory_id')->key('delete')
            ->example('get_memory_stats → mcp__vector-memory__get_memory_stats')->key('stats')
            ->example('clear_old_memories → mcp__vector-memory__clear_old_memories')->key('clear');

        $this->guideline('workflow-compilation-rules')
            ->text('Rules for how pseudo-syntax compiles to output.')
            ->example('PHP static methods → compiled function calls')->key('methods')
            ->example('Nested Operator::* → nested blocks with END markers')->key('nesting')
            ->example('Store::as(VAR) → $VAR in compiled output')->key('variable')
            ->example('Agent class names → kebab-case @agent- prefix')->key('agent-prefix')
            ->example('Runtime constants → platform-specific paths based on target')->key('paths');

        $this->rule('use-workflow-syntax')->high()
            ->text('When expressing complex workflows in guidelines, use workflow pseudo-syntax for clarity and platform-agnostic compilation.')
            ->why('Ensures consistent, deterministic, and maintainable workflow documentation.')
            ->onViolation('Refactor workflow descriptions to use proper pseudo-syntax operators.');
    }
}