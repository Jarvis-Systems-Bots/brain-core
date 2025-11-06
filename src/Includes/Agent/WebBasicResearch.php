<?php

declare(strict_types=1);

namespace BrainCore\Includes\Agent;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines basic web research capabilities for agents requiring simple information gathering.
Provides essential search and extraction guidelines without complex recursion logic.
PURPOSE
)]
class WebBasicResearch extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('web-search')
            ->text('Basic web search workflow.')
            ->example()
                ->phase('step-1', 'Define search query with temporal context (year)')
                ->phase('step-2', 'Execute DuckDuckGoWebSearch with 5-10 results')
                ->phase('step-3', 'Extract content from top 3-5 URLs')
                ->phase('step-4', 'Validate and synthesize findings');

        $this->guideline('source-priority')
            ->text('Prioritize authoritative sources.')
            ->example('Official documentation > GitHub repos > Community articles')
            ->example('Academic/governmental sources preferred')
            ->example('Cross-validate critical claims');

        $this->guideline('tools')
            ->text('Available web research tools.')
            ->example('DuckDuckGoWebSearch - primary search')
            ->example('UrlContentExtractor - content extraction')
            ->example('WebFetch - fallback for single URLs')
            ->example('Context7 - official library documentation');

        $this->rule('evidence-based')->high()
            ->text('All research findings must be backed by executed tool results.')
            ->why('Prevents speculation and ensures factual accuracy.')
            ->onViolation('Execute web tools before providing research conclusions.');
    }
}