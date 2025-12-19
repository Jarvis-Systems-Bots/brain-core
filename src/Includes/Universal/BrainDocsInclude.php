<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;
use BrainCore\Compilation\Operator;
use BrainCore\Compilation\Store;
use BrainCore\Compilation\Tools\BashTool;
use BrainCore\Compilation\Tools\ReadTool;
use BrainNode\Mcp\VectorMemoryMcp;

#[Purpose(<<<'PURPOSE'
Defines brain docs command protocol for real-time .docs/ indexing with YAML front matter parsing.
Compact workflow integration patterns for documentation discovery and validation.
PURPOSE
)]
class BrainDocsInclude extends IncludeArchetype
{
    protected function handle(): void
    {
        $this->guideline('brain-docs-command')
            ->text('Real-time documentation indexing and search via YAML front matter parsing.')
            ->example('brain docs - List all documentation files')->key('list-all')
            ->example('brain docs "keyword1,keyword2" - Search by keywords')->key('search')
            ->example('Returns: file path, name, description, part, type, date, version')->key('output')
            ->example('Keywords: comma-separated, case-insensitive, search in name/description/content')->key('format')
            ->example('Returns INDEX only (metadata), use Read tool to get file content')->key('index-only');

        $this->guideline('yaml-front-matter')
            ->text('Required structure for brain docs indexing.')
            ->example('---
name: "Document Title"
description: "Brief description"
part: 1
type: "guide"
date: "2025-11-12"
version: "1.0.0"
---')->key('structure')
            ->example('name, description: REQUIRED')->key('required')
            ->example('part, type, date, version: optional')->key('optional')
            ->example('type: tor (Terms of Service), guide, api, concept, architecture, reference')->key('types')
            ->example('part: split large docs (>500 lines) into numbered parts for readability')->key('part-usage')
            ->example('No YAML: returns path only. Malformed YAML: error + exit.')->key('behavior');

        $this->guideline('workflow-discovery')
            ->goal('Discover existing documentation before creating new')
            ->example()
            ->phase(BashTool::describe('brain docs "{keywords}"', Store::as('DOCS_INDEX')))
            ->phase(Operator::if(Store::get('DOCS_INDEX') . ' not empty', [
                ReadTool::call('{paths_from_index}'),
                'Update existing docs'
            ]));

        $this->guideline('workflow-multi-source')
            ->goal('Combine brain docs + vector memory for complete knowledge')
            ->example()
            ->phase(BashTool::describe('brain docs "{keywords}"', Store::as('STRUCTURED')))
            ->phase(VectorMemoryMcp::call('search_memories', '{query: "{keywords}", limit: 5}'))
            ->phase(Store::as('MEMORY', 'Vector search results'))
            ->phase('Merge: structured docs (primary) + vector memory (secondary)')
            ->phase('Fallback: if no structured docs, use vector memory + Explore agent');

        $this->rule('no-manual-indexing')->critical()
            ->text('NEVER create index.md or README.md for documentation indexing. brain docs handles all indexing automatically.')
            ->why('Manual indexing creates maintenance burden and becomes stale.')
            ->onViolation('Remove manual index files. Use brain docs exclusively.');

        $this->rule('markdown-only')->critical()
            ->text('ALL documentation MUST be markdown format with *.md extension. No other formats allowed.')
            ->why('Consistency, parseability, brain docs indexing requires markdown format.')
            ->onViolation('Convert non-markdown files to *.md or reject them from documentation.');

        $this->rule('documentation-not-codebase')->critical()
            ->text('Documentation is DESCRIPTION for humans, NOT codebase. Minimize code to absolute minimum.')
            ->why('Documentation must be human-readable. Code makes docs hard to understand and wastes tokens.')
            ->onViolation('Remove excessive code. Replace with clear textual description.');

        $this->rule('code-only-when-cheaper')->high()
            ->text('Include code ONLY when it is cheaper in tokens than text explanation AND no other choice exists.')
            ->why('Code is expensive, hard to read, not primary documentation format. Text first, code last resort.')
            ->onViolation('Replace code examples with concise textual description unless code is genuinely more efficient.');
    }
}
