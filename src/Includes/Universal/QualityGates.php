<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\BrainArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines the quality control checkpoints (gates) that all code, agents, and instruction artifacts must pass before deployment in the Brain ecosystem.
Each gate enforces objective metrics, structural validation, and automated CI actions to maintain production-level integrity.
PURPOSE
)]
class QualityGates extends BrainArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('gate-syntax')
            ->text('All source files must compile without syntax or lint errors.')
            ->example('Use linters: PHPStan level 10, ESLint strict mode, Go vet.')->key('validation')
            ->example('critical-errors = 0')->key('metric-1')
            ->example('warnings ≤ 5')->key('metric-2')
            ->example('block merge and trigger syntax-report job')->key('on-fail')
            ->example('mark code-quality-passed flag')->key('on-pass');

        $this->guideline('gate-tests')
            ->text('All unit, integration, and E2E tests must pass.')
            ->example('coverage ≥ 90%')->key('metric-1')
            ->example('failures = 0')->key('metric-2')
            ->example('Execute CI runners (PHPUnit, Jest, Go test).')->key('validation')
            ->example('abort pipeline and alert dev-channel')->key('on-fail')
            ->example('proceed to next gate')->key('on-pass');

        $this->guideline('gate-architecture')
            ->text('Project must follow declared architecture schemas and dependency boundaries.')
            ->example('Run architecture audit and dependency graph validator.')->key('validation')
            ->example('circular-dependencies = 0')->key('metric-1')
            ->example('forbidden-imports = 0')->key('metric-2')
            ->example('generate architecture-violations report')->key('on-fail')
            ->example('commit architectural compliance summary')->key('on-pass');

        $this->guideline('gate-xml-validation')
            ->text('All instruction files must be valid and match declared schemas.')
            ->example('Validate via CI regex and parser.')->key('validation')
            ->example('invalid-tags = 0')->key('metric-1')
            ->example('missing-sections = 0')->key('metric-2')
            ->example('reject commit with validation-error log')->key('on-fail')
            ->example('approve instruction import')->key('on-pass');

        $this->guideline('gate-token-efficiency')
            ->text('Instructions must not exceed their token compactness limits.')
            ->example('compact ≤ 300')->key('metric-1')
            ->example('normal ≤ 800')->key('metric-2')
            ->example('extended ≤ 1200')->key('metric-3')
            ->example('Estimate token usage pre-deploy using CI tokenizer.')->key('validation')
            ->example('truncate or split instruction and resubmit')->key('on-fail')
            ->example('allow merge')->key('on-pass');

        $this->guideline('gate-performance')
            ->text('Each agent must meet defined performance and reliability targets.')
            ->example('accuracy ≥ 0.95')->key('metric-1')
            ->example('latency ≤ 30s')->key('metric-2')
            ->example('stability ≥ 0.98')->key('metric-3')
            ->example('Run automated agent stress-tests and prompt-accuracy evaluation.')->key('validation')
            ->example('rollback agent to previous version and flag retraining')->key('on-fail')
            ->example('promote to production')->key('on-pass');

        $this->guideline('gate-memory-integrity')
            ->text('Vector or knowledge memory must load without corruption or drift.')
            ->example('memory-load-success = 100%')->key('metric-1')
            ->example('checksum-match = true')->key('metric-2')
            ->example('Run checksum comparison and recall accuracy tests.')->key('validation')
            ->example('trigger memory-repair job')->key('on-fail')
            ->example('continue to optimization phase')->key('on-pass');

        $this->guideline('gate-dependencies')
            ->text('All dependencies must pass vulnerability scan.')
            ->example('Run npm audit, composer audit, go list -m -u all.')->key('validation')
            ->example('critical = 0')->key('metric-1')
            ->example('high ≤ 1')->key('metric-2')
            ->example('block merge and notify security channel')->key('on-fail')
            ->example('mark dependency-scan-passed')->key('on-pass');

        $this->guideline('gate-env-compliance')
            ->text('Environment variables and secrets must conform to policy.')
            ->example('Check against CI secret-policy ruleset.')->key('validation')
            ->example('exposed-keys = 0')->key('metric-1')
            ->example('policy-violations = 0')->key('metric-2')
            ->example('remove secret and alert owner')->key('on-fail')
            ->example('log compliance success')->key('on-pass');

        $this->guideline('global-validation-quality')
            ->example('All gates must return pass before deployment is allowed.')
            ->example('Failures automatically trigger rollback and CI notification.')
            ->example('CI pipeline must generate a signed quality report for each build.');

        $this->guideline('meta-controls-quality')
            ->text('All quality gates enforced via CI with automated actions and comprehensive logging.');
    }
}