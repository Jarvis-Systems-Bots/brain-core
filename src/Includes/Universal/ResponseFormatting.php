<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\BrainArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
CI Regex Validator for Response Formatting Structure.
Ensures that all Cloud Code agent responses comply with the unified response format for consistency and quality control.
PURPOSE
)]
class ResponseFormatting extends BrainArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('validation-description')
            ->text('This CI validator ensures that all Cloud Code agent responses comply with the unified response format. It can be used in CI/CD pipelines or locally before deployment to verify structure, hierarchy, token range, and tone rules.');

        $this->guideline('regex-must-contain')
            ->example('^<response(?: type="(compact|normal|extended|error)")?>[\s\S]*?</response>$')
            ->example('<summary>[\s\S]+?</summary>')
            ->example('<details>[\s\S]+?</details>')
            ->example('<next-steps>[\s\S]+?</next-steps>');

        $this->guideline('regex-optional-contain')
            ->example('<sources>[\s\S]+?</sources>')
            ->example('<risks>[\s\S]+?</risks>')
            ->example('<locale>[a-z]{2,3}</locale>')
            ->example('<code-policy>[\s\S]+?</code-policy>');

        $this->guideline('regex-prohibited')
            ->example('[🚀🔥💩😅😂]')
            ->example('\b(lol|wtf|omg|bro|dude)\b');

        $this->guideline('token-limits')
            ->example('compact = 300')
            ->example('normal = 800')
            ->example('extended = 1200');

        $this->guideline('validation-logic')
            ->example('Validate presence of response, summary, details, and next-steps tags.')
            ->example('Ensure no prohibited slang or emojis exist.')
            ->example('If type = compact → estimated token count ≤ 300.')
            ->example('If type = normal → estimated token count ≤ 800.')
            ->example('If type = extended → estimated token count ≤ 1200.')
            ->example('If type = error → must include a clear reason and a proposed alternative solution.');

        $this->guideline('meta-controls-response-formatting')
            ->text('CI validator optimized for automated response format verification across all agents.');
    }
}