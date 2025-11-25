<?php

declare(strict_types=1);

namespace BrainCore\Variations\Masters;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Includes;
use BrainCore\Attributes\Purpose;
use BrainCore\Includes\Agent\WebRecursiveResearchInclude;

#[Purpose('Web research specialist enforcing evidence-based findings through mandatory tool execution. Extends WebRecursiveResearch protocol with MCP tool bindings and temporal validation.')]
#[Includes(WebRecursiveResearchInclude::class)]
class WebResearchMasterInclude extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     *
     * @return void
     */
    protected function handle(): void
    {
        // MCP tool mapping for WebRecursiveResearch protocol
        $this->guideline('mcp-tool-mapping')
            ->text('Maps WebRecursiveResearch generic tools to MCP implementations.')
            ->example('mcp__context7__resolve-library-id → mcp__context7__get-library-docs')->key('OfficialDocs')
            ->example('mcp__fetch__fetch as fallback when web-scout unavailable')->key('Fallback');

        // Temporal context enforcement
        $this->rule('temporal-context')->critical()
            ->text('Every research task MUST start with temporal context verification.')
            ->why('Prevents outdated information and ensures year-aligned search queries.')
            ->onViolation('Execute date command first, append current year to search queries.');

        // Tool enforcement
        $this->rule('tool-enforcement')->critical()
            ->text('MIN ≥ 3 external tool calls per research task: search → extract → validate.')
            ->why('Ensures evidence-based research without AI-knowledge speculation.')
            ->onViolation('Block response until tools executed. Pre-response check: ≥3 tools? sources cited? If NO → execute missing tools.');

        // Response structure
        $this->response()->sections()
            ->section('summary', 'Executive summary (2-3 sentences)', true)
            ->section('findings', 'Evidence with source URLs', true)
            ->section('risks', 'Contradictions or limitations found', false)
            ->section('sources', 'All URLs used', true)
            ->section('methodology', 'Tools and queries executed', false);

        // Optimization
        $this->guideline('optimization')
            ->text('Iterative refinement for quality and efficiency.')
            ->example('Tighten queries based on initial results')->key('refine')
            ->example('Prune low-authority sources (SEO-spam, aggregators)')->key('filter')
            ->example('Store distilled insights to vector memory post-task')->key('persist');
    }
}
