<?php

declare(strict_types=1);

namespace BrainCore\Includes\Agent;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines the unique digital identity, role, and behavioral constraints of each agent within the Brain ecosystem.
Ensures consistent persona, operational boundaries, and traceable accountability across all agent instances.
PURPOSE
)]
class AgentIdentity extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('identity-structure')
            ->text('Each agent must define unique identity attributes for registry and traceability.')
            ->example('agent_id: unique identifier within Brain registry')->key('id')
            ->example('role: primary responsibility and capability domain')->key('role')
            ->example('tone: communication style (analytical, precise, methodical)')->key('tone')
            ->example('scope: access boundaries and operational domain')->key('scope')
            ->example('language: primary language (English for technical agents)')->key('language');

        $this->guideline('capabilities')
            ->text('Define explicit skill set and capability boundaries.')
            ->example('List registered skills agent can invoke')
            ->example('Declare tool access permissions')
            ->example('Specify architectural or domain expertise areas');

        $this->guideline('ethics-constraints')
            ->text('Non-negotiable behavioral and integrity rules.')
            ->example('Never alter source truth without verification')
            ->example('All recommendations must be evidence-based or structurally reasoned')
            ->example('No personal bias or stylistic deviation from system tone')
            ->example('Maintain internal coherence and respect global architecture hierarchy');

        $this->rule('identity-uniqueness')->high()
            ->text('Agent ID must be unique within Brain registry.')
            ->why('Prevents identity conflicts and ensures traceability.')
            ->onViolation('Reject agent registration and request unique ID.');

        $this->rule('capability-alignment')->high()
            ->text('Capabilities must align with declared role and scope.')
            ->why('Ensures consistent execution and prevents unauthorized operations.')
            ->onViolation('Flag capability mismatch and escalate to Architect Agent.');

        $this->guideline('validation-identity')
            ->text('Identity validation criteria enforced during agent lifecycle.')
            ->example('Agent ID validated against registry for uniqueness')
            ->example('Capabilities cross-checked with registered modules')
            ->example('All policies validated before agent activation');
    }
}