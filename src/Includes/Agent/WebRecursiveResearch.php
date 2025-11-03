<?php

declare(strict_types=1);

namespace BrainCore\Includes\Agent;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines recursive web research protocol for all Cloud Code agents.
Establishes strict boundaries for querying, recursion depth, data validation, and aggregation.
Ensures efficient and reliable autonomous information gathering with source integrity.
PURPOSE
)]
class WebRecursiveResearch extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('phase-query')
            ->text('Goal: Initial query formulation and submission.')
            ->example('On user task requiring external information not found in local memory or knowledge banks')->key('trigger')
            ->example()
                ->phase('logic-1', 'Generate initial query string using task context and keywords')
                ->phase('logic-2', 'Submit query to web interface or connected search API')
                ->phase('logic-3', 'Limit requests to avoid redundancy')
                ->phase('validation', 'Query must contain at least one domain keyword and one context keyword');

        $this->guideline('limits-query')
            ->text('Query phase resource limits.')
            ->example('max-queries = 3')
            ->example('timeout = 20s');

        $this->guideline('phase-evaluation')
            ->text('Goal: Rank and filter initial search results.')
            ->example('After initial set of search results is returned')->key('trigger')
            ->example()
                ->phase('logic-1', 'Rank results by relevance score (title + snippet match)')
                ->phase('logic-2', 'Discard sources with low domain credibility or duplicates')
                ->phase('validation', 'At least 60% of selected results must contain original (non-referenced) content');

        $this->guideline('metrics-evaluation')
            ->text('Evaluation phase quality metrics.')
            ->example('avg-relevance ≥ 0.75')
            ->example('unique-sources ≥ 3');

        $this->guideline('phase-recursion')
            ->text('Goal: Follow references and gather missing information recursively.')
            ->example('When extracted data contains references or partial answers requiring further lookup')->key('trigger')
            ->example()
                ->phase('logic-1', 'Extract new subqueries from referenced entities or hyperlinks')
                ->phase('logic-2', 'Re-enter query phase recursively with new subquery context')
                ->phase('logic-3', 'Merge responses only if they pass validation threshold')
                ->phase('validation', 'Recursive call permitted only when parent data incomplete or ambiguous');

        $this->guideline('limits-recursion')
            ->text('Recursion safety limits.')
            ->example('max-depth = 3')
            ->example('max-total-requests = 10')
            ->example('Abort if two consecutive recursive loops yield duplicate or irrelevant data')->key('abort-condition');

        $this->guideline('fallback-recursion')
            ->text('Recursion fallback action.')
            ->example('If recursion exceeds limit, summarize partial data and mark as incomplete');

        $this->guideline('phase-aggregation')
            ->text('Goal: Merge and deduplicate collected information.')
            ->example('After recursion completes or all sources exhausted')->key('trigger')
            ->example()
                ->phase('logic-1', 'Extract factual statements and numerical data from all validated results')
                ->phase('logic-2', 'Deduplicate and merge overlapping information')
                ->phase('logic-3', 'Rank key insights by frequency and source trust score')
                ->phase('validation', 'At least two independent sources must support each retained fact');

        $this->guideline('metrics-aggregation')
            ->text('Aggregation quality metrics.')
            ->example('aggregated-facts ≥ 5')
            ->example('confidence-score ≥ 0.85');

        $this->guideline('phase-output')
            ->text('Goal: Format and store research results.')
            ->example('Once aggregation phase validated')->key('trigger')
            ->example()
                ->phase('logic-1', 'Format final summary: key findings, numeric data, sources')
                ->phase('logic-2', 'Include reference list with normalized URLs')
                ->phase('logic-3', 'Store research record in vector memory for future recall')
                ->phase('validation-1', 'Output must contain no speculative or unverifiable content')
                ->phase('validation-2', 'All sources must have active URLs and valid protocols')
                ->phase('fallback', 'If output incomplete, retry aggregation with broader search scope');

        $this->rule('recursion-depth-limit')->high()
            ->text('Never exceed three recursion layers per research chain.')
            ->why('Prevents infinite loops and resource exhaustion.')
            ->onViolation('Abort recursion and summarize partial results.');

        $this->rule('token-limit-awareness')->high()
            ->text('Abort search if token usage exceeds 90% of limit.')
            ->why('Prevents context overflow during research operations.')
            ->onViolation('Terminate search and return partial results with warning.');

        $this->rule('recursion-cooldown')->medium()
            ->text('Enforce cooldown of 30s between recursion cycles.')
            ->why('Prevents API rate limiting and system overload.')
            ->onViolation('Wait for cooldown period before next cycle.');

        $this->guideline('source-integrity-policy')
            ->text('Source quality and credibility requirements.')
            ->example('Discard domains flagged as AI-generated or low-credibility')
            ->example('Prioritize academic, governmental, and peer-reviewed sources');

        $this->guideline('output-integrity-policy')
            ->text('Output structure and metadata requirements.')
            ->example('Always include source list in response XML block <sources>')
            ->example('Reject outputs missing origin metadata');
    }
}