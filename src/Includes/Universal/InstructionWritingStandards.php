<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\BrainArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines the unified standard for authoring, maintaining, and validating all instructions used by agents and subsystems within the Brain architecture.
Ensures clarity, predictability, and structural consistency across all instruction documents.
PURPOSE
)]
class InstructionWritingStandards extends BrainArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('principle-clarity')
            ->text('Every instruction must be explicit, unambiguous, and logically structured.');

        $this->guideline('principle-minimalism')
            ->text('Avoid unnecessary prose, redundancy, and human-readable filler text.');

        $this->guideline('principle-machine-precision')
            ->text('Instructions must be designed primarily for machine parsing and agent execution.');

        $this->guideline('principle-consistency')
            ->text('All documents must follow the same hierarchy, indentation, and naming schema.');

        $this->guideline('principle-validation')
            ->text('Each instruction must be self-verifiable through automated CI checks.');

        $this->guideline('required-elements')
            ->example('meta: Defines version, purpose, and modification date.')
            ->example('principles: Lists core conceptual guidelines.')
            ->example('rules: Contains mandatory behavioral or design constraints.')
            ->example('style: Defines tone, formatting, and lexical requirements.')
            ->example('validation: Specifies regex or logic validation used in CI.');

        $this->guideline('optional-elements')
            ->example('examples: Provides minimal reference structures for developers or agent synthesis.')
            ->example('references: Links to related framework or meta-documents.');

        $this->rule('r1')->high()
            ->text('Each instruction file must use strict format, UTF-8 encoding, and validated closing tags.')
            ->why('Ensures parseability and consistency.')
            ->onViolation('Reject file in CI validation.');

        $this->rule('r2')->high()
            ->text('Do not include Markdown, plain prose, or uncontrolled text segments.')
            ->why('Maintains machine-first design.')
            ->onViolation('Flag as invalid format.');

        $this->rule('r3')->medium()
            ->text('Every section must have at least one descriptive element with logical meaning.')
            ->why('Prevents empty or meaningless sections.')
            ->onViolation('Request section content.');

        $this->rule('r4')->medium()
            ->text('Instructions must not exceed 1200 tokens unless explicitly flagged as extended.')
            ->why('Controls memory and processing overhead.')
            ->onViolation('Truncate or split instruction.');

        $this->rule('r5')->medium()
            ->text('All parameters, variables, or placeholders must be enclosed in double braces: {{variable_name}}.')
            ->why('Standardizes variable notation.')
            ->onViolation('Auto-correct or warn developer.');

        $this->guideline('style-tone')
            ->text('Professional, directive, and context-neutral. Never conversational or emotional.');

        $this->guideline('style-formatting')
            ->example('2 spaces per level')->key('indentation')
            ->example('Use kebab-case for IDs and snake_case for variable placeholders')->key('naming')
            ->example('Tag names are lowercase only')->key('capitalization');

        $this->guideline('style-language')
            ->text('English only for all instruction metadata and logic blocks.');

        $this->guideline('validation-regex')
            ->example('Ensure all mandatory sections exist (meta, rules, style).')
            ->example('Reject any Markdown syntax or human prose markers.')
            ->example('Verify closing tags and hierarchy via parser.')
            ->example('Validate token count based on type (compact ≤ 300, normal ≤ 800, extended ≤ 1200).');

        $this->guideline('meta-controls-standards')
            ->text('Ensures all instruction documents follow unified authoring and validation standards.');
    }
}