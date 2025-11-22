<?php

declare(strict_types=1);

namespace BrainCore\Includes\Commands\Doc;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose('The Work command is a guided workflow for writing documentation for a project.')]
class DocWorkInclude extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     *
     * @return void
     */
    protected function handle(): void
    {
        // =========================================================================
        // IRON RULES
        // =========================================================================
        $this->rule('max-interactivity')->critical()
            ->text('MUST engage user with clarifying questions via AskUserQuestion tool. NEVER assume.')
            ->why('Assumptions lead to misalignment and rework.')
            ->onViolation('Stop and ask clarifying question.');

        $this->rule('500-line-limit')->critical()
            ->text('Each file MUST NOT exceed 500 lines. Split into part-1.md, part-2.md if needed.')
            ->why('Maintains readability.')
            ->onViolation('Split content with clear naming and cross-references.');

        $this->rule('docs-folder-structure')->high()
            ->text('All documentation in .docs/ with hierarchy: features/, modules/, concepts/, architecture/, guides/, api/, tor/, reference/')
            ->why('Ensures organization and discoverability.')
            ->onViolation('Restructure to comply with folder hierarchy.');

        $this->rule('evidence-based')->high()
            ->text('Content MUST be based on codebase exploration, file reading, or verified web research.')
            ->why('Prevents speculation.')
            ->onViolation('Use Explore agent, Read tool, or Web Research Master first.');

        $this->rule('validation-checkpoints')->high()
            ->text('Obtain user approval at: structure proposal, first draft section, before finalization.')
            ->why('Ensures alignment with expectations.')
            ->onViolation('Pause and request validation.');

        // =========================================================================
        // ARGUMENTS FORMAT
        // =========================================================================
        $this->guideline('arguments-format')
            ->text('$ARGUMENTS accepts: feature:X, module:X, concept:X, file:X, topic:X, or plain text description.');

        // =========================================================================
        // WORKFLOW PHASES
        // =========================================================================
        $this->guideline('phase-1-understanding')
            ->text('Phase 1: Understand what to document through maximum interactivity.')
            ->example()
            ->phase('step-1', 'Parse $ARGUMENTS to identify target type and scope')
            ->phase('step-2', 'Ask: What aspects? What depth? Target audience? Use cases?')
            ->phase('step-3', 'Use AskUserQuestion until crystal clear')
            ->phase('validation', 'Requirements clarity >= 95%');

        $this->guideline('phase-2-information-gathering')
            ->text('Phase 2: Gather comprehensive information.')
            ->example()
            ->phase('step-1', 'Task(subagent_type="Explore") for codebase structure')
            ->phase('step-2', 'Read relevant files')
            ->phase('step-3', 'Search vector memory: mcp__vector-memory__search_memories')
            ->phase('step-4', 'If external context needed: Task(@agent-web-research-master)')
            ->phase('validation', 'Evidence-based content >= 95%');

        $this->guideline('phase-3-structure-proposal')
            ->text('Phase 3: Propose structure and get approval.')
            ->example()
            ->phase('step-1', 'Design folder hierarchy within .docs/')
            ->phase('step-2', 'Create outline: sections, code examples, diagrams')
            ->phase('step-3', 'Estimate length, plan multi-file split if > 500 lines')
            ->phase('step-4', 'AskUserQuestion for approval')
            ->phase('validation', 'User explicitly approves structure');

        $this->guideline('phase-4-writing')
            ->text('Phase 4: Write professional documentation.')
            ->example()
            ->phase('step-1', 'Write first major section')
            ->phase('step-2', 'Use TodoWrite to track progress')
            ->phase('step-3', 'Show first section to user for validation')
            ->phase('step-4', 'Continue based on feedback')
            ->phase('step-5', 'Include: code examples, architecture diagrams (text-based), use cases')
            ->phase('validation', 'Each section <= 500 lines, user validates first section');

        $this->guideline('phase-5-finalization')
            ->text('Phase 5: Review, finalize, deliver.')
            ->example()
            ->phase('step-1', 'Final review: 500-line limits, cross-references, completeness')
            ->phase('step-2', 'Create TOC if multi-file')
            ->phase('step-3', 'Present final for approval')
            ->phase('step-4', 'Store insights: mcp__vector-memory__store_memory')
            ->phase('step-5', 'Write files to .docs/')
            ->phase('validation', 'User approves final documentation');

        // =========================================================================
        // YAML FRONT MATTER
        // =========================================================================
        $this->rule('yaml-front-matter')->critical()
            ->text('EVERY file MUST start with YAML front matter for brain docs indexing.')
            ->why('brain docs parses metadata for index and search.')
            ->onViolation('Add YAML front matter before markdown content.');

        $this->guideline('yaml-structure')
            ->text('Required YAML structure: name (required), description (required), part/type/date/version (optional).')
            ->example('---
name: "Document Title"
description: "Brief description"
part: 1
type: "guide"
date: "2025-11-20"
version: "1.0.0"
---')->key('structure');

        // =========================================================================
        // QUALITY STANDARDS
        // =========================================================================
        $this->guideline('professional-writing')
            ->text('Technical writing standards.')
            ->example('Clear, concise language')->key('language')
            ->example('Logical structure with proper hierarchy')->key('structure')
            ->example('Code examples with context (minimal, only when cheaper than text)')->key('code')
            ->example('Text-based architecture diagrams')->key('diagrams')
            ->example('Cross-references to related docs')->key('refs')
            ->example('Proper markdown with syntax highlighting')->key('format');

        // =========================================================================
        // FILE NAMING
        // =========================================================================
        $this->guideline('file-naming')
            ->text('Naming conventions: lowercase with hyphens, no spaces.')
            ->example('Single: topic-name.md')->key('single')
            ->example('Multi-part: topic-name-part-1.md, topic-name-part-2.md')->key('multi')
            ->example('Index: README.md for folder overview')->key('index');

        // =========================================================================
        // CROSS-REFERENCING
        // =========================================================================
        $this->guideline('cross-referencing')
            ->text('Use relative paths for cross-references.')
            ->example('[See Part 2](./topic-name-part-2.md)')->key('part')
            ->example('[Related concept](../concepts/delegation.md)')->key('concept');

        // =========================================================================
        // TOOL INTEGRATION
        // =========================================================================
        $this->guideline('tool-integration')
            ->text('Tool usage patterns.')
            ->example('Explore: Task(subagent_type="Explore", prompt="...")')->key('explore')
            ->example('Web research: Task(@agent-web-research-master, "...")')->key('web')
            ->example('Memory search: mcp__vector-memory__search_memories')->key('memory-search')
            ->example('Memory store: mcp__vector-memory__store_memory')->key('memory-store');

        // =========================================================================
        // DIRECTIVE
        // =========================================================================
        $this->guideline('directive')
            ->text('Ask constantly! Explore thoroughly! Validate frequently! Write professionally!');
    }
}
