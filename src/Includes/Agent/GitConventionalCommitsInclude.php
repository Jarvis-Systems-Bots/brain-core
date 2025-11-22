<?php

declare(strict_types=1);

namespace BrainCore\Includes\Agent;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Conventional commits specification for semantic versioning and changelog generation.
Enforces standardized commit message format across Brain ecosystem.
PURPOSE
)]
class GitConventionalCommitsInclude extends IncludeArchetype
{
    protected function handle(): void
    {
        $this->rule('format-required')->critical()
            ->text('Commit message MUST follow pattern: type(scope?): message')
            ->why('Enables semantic versioning, automated changelogs, and CI validation.')
            ->onViolation('Reject commit. Fix format before proceeding.');

        $this->guideline('format')
            ->text('Commit message format specification.')
            ->example('type(scope?): message')->key('pattern')
            ->example('feat(api): add user authentication endpoint')->key('valid')
            ->example('fix(ui): correct button alignment')->key('valid-2')
            ->example('Scope: optional, lowercase, alphanumeric (e.g., core, auth, ui)')->key('scope')
            ->example('Message: ≤72 chars, no trailing punctuation, imperative mood')->key('message');

        $this->guideline('types')
            ->text('Valid commit types.')
            ->example('New feature or capability')->key('feat')
            ->example('Bug fix')->key('fix')
            ->example('Documentation only')->key('docs')
            ->example('Code formatting without logic change')->key('style')
            ->example('Restructuring without feature/bug impact')->key('refactor')
            ->example('Adding or modifying tests')->key('test')
            ->example('Build system or dependencies')->key('build')
            ->example('Maintenance or tooling')->key('chore')
            ->example('Performance improvement')->key('perf')
            ->example('CI/CD pipeline updates')->key('ci');

        $this->rule('issue-linking')->high()
            ->text('Commits fixing issues MUST reference: Closes #ID or Fixes #ID')
            ->why('Enables automatic issue tracking and traceability.')
            ->onViolation('Add issue reference if applicable.');

        $this->guideline('breaking-changes')
            ->text('Breaking API changes require BREAKING CHANGE footer.')
            ->example('feat(api): update auth format\n\nBREAKING CHANGE: new header scheme required.')->key('example')
            ->example('Footer must describe migration steps')->key('requirement');
    }
}
