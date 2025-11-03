<?php

declare(strict_types=1);

namespace BrainCore\Includes\Agent;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Establishes standardized commit message format for all Brain and Agent repositories.
Ensures semantic versioning compatibility, automated changelog generation, and CI validation consistency.
Enforces conventional commits specification for maintainable version control.
PURPOSE
)]
class GitConventionalCommits extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('format-structure')
            ->text('Commit message structure and pattern.')
            ->example('type(scope?): message')->key('pattern')
            ->example('Commit message must begin with valid type, followed by optional scope in parentheses, colon, and concise message')->key('description');

        $this->guideline('format-examples')
            ->text('Valid and invalid commit message examples.')
            ->example('feat(api): add user authentication endpoint')->key('valid-1')
            ->example('fix(ui): correct button alignment')->key('valid-2')
            ->example('updated files')->key('invalid');

        $this->guideline('types')
            ->text('Valid commit types and their meanings.')
            ->example('Introduces new feature or capability')->key('feat')
            ->example('Fixes bug or defect')->key('fix')
            ->example('Documentation-only changes')->key('docs')
            ->example('Code formatting or stylistic updates without logic change')->key('style')
            ->example('Code restructuring without feature/bug impact')->key('refactor')
            ->example('Adding or modifying tests')->key('test')
            ->example('Changes related to build system or dependencies')->key('build')
            ->example('Routine maintenance or tooling changes')->key('chore')
            ->example('Performance improvement')->key('perf')
            ->example('Continuous integration or pipeline updates')->key('ci');

        $this->guideline('scope-definition')
            ->text('Scope specification for multi-module repositories.')
            ->example('Specifies context or module affected by change')->key('description')
            ->example('Optional but recommended for multi-module repositories')->key('requirement')
            ->example('feat(core): optimize database caching')->key('example-1')
            ->example('fix(auth): token refresh error')->key('example-2');

        $this->guideline('validation-criteria')
            ->text('Commit message validation requirements.')
            ->example('Commit message must start with valid type listed in types')
            ->example('Message text must be ≤ 72 characters')
            ->example('Scope, if present, must be alphanumeric and use lowercase')
            ->example('Messages must not end with punctuation')
            ->example('Commit body (if present) must explain reasoning, not restate title');

        $this->guideline('issue-linking')
            ->text('Issue reference linking in commits.')
            ->example('Each commit referencing issue must use syntax: Closes #ISSUE_ID or Fixes #ISSUE_ID')->key('rule')
            ->example('fix(core): resolve crash on init (Closes #342)')->key('example')
            ->example('Referenced issue must exist in same repository or linked project')->key('validation');

        $this->guideline('breaking-changes')
            ->text('Breaking change declaration in commits.')
            ->example('If commit introduces breaking API change, append BREAKING CHANGE section in body')->key('rule')
            ->example('feat(api): update auth token format\n\nBREAKING CHANGE: clients must use new header scheme.')->key('valid-example')
            ->example('BREAKING CHANGE must appear only once and describe required migration steps')->key('validation');

        $this->guideline('fallback-actions')
            ->text('Actions when commit validation fails.')
            ->example('If commit message invalid, reject pre-commit hook and request correction')
            ->example('If automated fix possible (e.g., missing colon), CI applies correction and revalidates')
            ->example('If type unrecognized, classify as chore and flag for PM review');
    }
}