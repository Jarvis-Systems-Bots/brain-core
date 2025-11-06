<?php

declare(strict_types=1);

namespace BrainCore\Includes\Agent;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines standardized PHP Archetype system for Brain ecosystem.
Ensures consistent structure for creating Brains, Agents, Skills, Commands, and Includes.
Provides validation rules and best practices for DTO-based architecture with Builder API.
PURPOSE
)]
class ArchitectTemplateSystem extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('archetype-brain')
            ->text('Brain archetype structure for orchestration.')
            ->example('Extends BrainArchetype')->key('inheritance')
            ->example('Purpose attribute with clear description')->key('required-1')
            ->example('handle() method with include chain')->key('required-2')
            ->example('Include Universal and Brain-specific includes')->key('pattern')
            ->example('Define orchestration rules with ->rule()->critical()')->key('content');

        $this->guideline('archetype-agent')
            ->text('Agent archetype structure for specialized execution.')
            ->example('Extends AgentArchetype')->key('inheritance')
            ->example('Purpose attribute defining agent capability domain')->key('required-1')
            ->example('handle() method with guidelines and rules')->key('required-2')
            ->example('Include Universal and Agent-specific includes')->key('pattern')
            ->example('Define capabilities, constraints, and execution boundaries')->key('content');

        $this->guideline('archetype-skill')
            ->text('Skill archetype structure for reusable capabilities.')
            ->example('Extends SkillArchetype')->key('inheritance')
            ->example('Purpose attribute describing skill function')->key('required-1')
            ->example('handle() method with focused guidelines')->key('required-2')
            ->example('Stateless, focused instruction sets')->key('pattern')
            ->example('Can be invoked by multiple agents')->key('reusability');

        $this->guideline('archetype-command')
            ->text('Command archetype structure for user-facing workflows.')
            ->example('Extends CommandArchetype')->key('inheritance')
            ->example('Purpose attribute explaining command intent')->key('required-1')
            ->example('handle() method with execution flow')->key('required-2')
            ->example('Define delegation patterns and validation steps')->key('pattern')
            ->example('Compiles to executable commands')->key('output');

        $this->guideline('archetype-include')
            ->text('Include archetype structure for compile-time fragments.')
            ->example('Extends IncludeArchetype')->key('inheritance')
            ->example('Purpose attribute describing shared functionality')->key('required-1')
            ->example('handle() method with reusable guidelines/rules')->key('required-2')
            ->example('Merges into parent during compilation')->key('behavior')
            ->example('Zero runtime footprint after compilation')->key('optimization');

        $this->guideline('builder-api-patterns')
            ->text('Standard Builder API usage patterns.')
            ->example('$this->rule(id)->severity()->text()->why()->onViolation()')->key('rules')
            ->example('$this->guideline(id)->text()->example()')->key('guidelines')
            ->example('->example(value)->key(name) for key-value pairs')->key('kv-examples')
            ->example('->example()->phase(id, text) for workflows')->key('phase-examples')
            ->example('$this->include(ClassName::class) for includes')->key('includes');

        $this->guideline('purpose-attribute')
            ->text('Purpose attribute requirements for all archetypes.')
            ->example('Use heredoc syntax with PURPOSE marker')
            ->example('Describe WHAT archetype does and WHY it exists')
            ->example('English only, concise (2-3 sentences)')
            ->example('No implementation details, only intent');

        $this->rule('strict-typing')->critical()
            ->text('All archetype files must use declare(strict_types=1).')
            ->why('Ensures type safety and prevents runtime errors.')
            ->onViolation('Add strict_types declaration at file start.');

        $this->rule('handle-method-required')->critical()
            ->text('All archetypes must implement protected handle() method.')
            ->why('Builder API logic executed during compilation.')
            ->onViolation('Add handle() method with archetype logic.');

        $this->rule('namespace-consistency')->high()
            ->text('Archetypes must use correct namespace based on location.')
            ->why('Ensures autoloading and organizational clarity.')
            ->onViolation('Correct namespace to match directory structure.');

        $this->guideline('validation-criteria')
            ->text('Archetype validation requirements.')
            ->example('File compiles without syntax errors')
            ->example('Purpose attribute present and non-empty')
            ->example('handle() method defined and accessible')
            ->example('No direct output or echo statements')
            ->example('Builder API methods used correctly');

        $this->guideline('naming-conventions')
            ->text('Standard naming patterns for archetypes.')
            ->example('PascalCase class names')
            ->example('kebab-case for guideline/rule IDs')
            ->example('Descriptive, domain-specific names')
            ->example('Avoid generic names like Helper, Util, Manager');

        $this->guideline('include-system-usage')
            ->text('Best practices for include chains.')
            ->example('Include Universal constraints first')
            ->example('Include domain-specific includes next')
            ->example('Define archetype-specific content last')
            ->example('Avoid circular includes')
            ->example('Maximum include depth: 255 levels');
    }
}
