<?php

declare(strict_types=1);

namespace BrainCore\Archetypes;

use BrainCore\Archetypes\Traits\ExtractAttributesTrait;
use BrainCore\Archetypes\Traits\GuidelinesTrait;
use BrainCore\Archetypes\Traits\IronRulesTrait;
use BrainCore\Archetypes\Traits\MetasTrait;
use BrainCore\Archetypes\Traits\PurposeTrait;
use BrainCore\Archetypes\Traits\ResponseTrait;
use BrainCore\Architectures\ArchetypeArchitecture;

abstract class SkillArchetype extends ArchetypeArchitecture
{
    use MetasTrait;
    use PurposeTrait;
    use ResponseTrait;
    use IronRulesTrait;
    use GuidelinesTrait;
    use ExtractAttributesTrait;

    /**
     * Default element name.
     *
     * @return non-empty-string
     */
    protected static function defaultElement(): string
    {
        return 'skill';
    }
}
