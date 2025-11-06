<?php

declare(strict_types=1);

namespace BrainCore\Includes\Agent;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines temporal awareness and recency validation mechanism for all agents.
Ensures agents always reason and respond within correct chronological, technological, and contextual timeframe.
Prevents outdated recommendations and maintains temporal coherence across all operations.
PURPOSE
)]
class TemporalContextAwareness extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('temporal-awareness')
            ->text('Maintain awareness of current time and content recency.')
            ->example('Initialize with current date/time before reasoning')
            ->example('Prefer recent information over outdated sources')
            ->example('Validate external content timestamps')
            ->example('Flag deprecated frameworks or libraries');

        $this->rule('temporal-check')->high()
            ->text('Verify temporal context before major operations.')
            ->why('Ensures recommendations reflect current state.')
            ->onViolation('Initialize temporal context first.');

        $this->guideline('recency-validation')
            ->text('Content recency guidelines.')
            ->example('Technical docs: prefer content < 1 year old')
            ->example('Frameworks: check for deprecated versions')
            ->example('External sources: validate publication date');
    }
}