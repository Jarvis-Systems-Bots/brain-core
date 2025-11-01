<?php

declare(strict_types=1);

namespace BrainCore\Includes\Brain;

use BrainCore\Archetypes\BrainArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Lightweight Brain-level delegation reference.
Declares formal existence of Brain's delegation control layer for CI validation.
Full procedural logic resides in delegation protocols.
PURPOSE
)]
class AgentDelegation extends BrainArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('linkage')
            ->text('Brain cannot perform direct agent-to-agent delegation; must route via Architect Agent.')
            ->example('Brain Core')->key('source')
            ->example('delegation protocols')->key('delegation-control')
            ->example('pre-action tool validation enforcement')->key('validation-pipeline');

        $this->guideline('meta-controls')
            ->text('Stub structural reference only, no executable logic.')
            ->example('Delegation stub registered for CI awareness and architecture completeness.');
    }
}
