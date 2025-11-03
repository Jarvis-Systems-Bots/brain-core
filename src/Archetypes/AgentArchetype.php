<?php

declare(strict_types=1);

namespace BrainCore\Archetypes;

use BrainCore\Archetypes\Traits\DeterminismTrait;
use BrainCore\Archetypes\Traits\ExtractAttributesTrait;
use BrainCore\Archetypes\Traits\GuidelinesTrait;
use BrainCore\Archetypes\Traits\IronRulesTrait;
use BrainCore\Archetypes\Traits\PurposeTrait;
use BrainCore\Archetypes\Traits\ResponseTrait;
use BrainCore\Archetypes\Traits\StyleTrait;
use BrainCore\Architectures\ArchetypeArchitecture;

abstract class AgentArchetype extends ArchetypeArchitecture
{
    use StyleTrait;
    use PurposeTrait;
    use ResponseTrait;
    use IronRulesTrait;
    use GuidelinesTrait;
    use DeterminismTrait;
    use ExtractAttributesTrait;

    /**
     * Default element name.
     *
     * @return non-empty-string
     */
    protected static function defaultElement(): string
    {
        return 'system';
    }
}
