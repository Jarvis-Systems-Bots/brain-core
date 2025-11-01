<?php

declare(strict_types=1);

namespace BrainCore\Archetypes;

use BrainCore\Archetypes\Traits\DeterminismTrait;
use BrainCore\Archetypes\Traits\ExtractAttributesTrait;
use BrainCore\Archetypes\Traits\GuidelinesTrait;
use BrainCore\Archetypes\Traits\IronRulesTrait;
use BrainCore\Archetypes\Traits\MetasTrait;
use BrainCore\Archetypes\Traits\PurposeTrait;
use BrainCore\Archetypes\Traits\ResponseTrait;
use BrainCore\Archetypes\Traits\StyleTrait;
use BrainCore\Architectures\ArchetypeArchitecture;

abstract class BrainArchetype extends ArchetypeArchitecture
{
    use MetasTrait;
    use StyleTrait;
    use PurposeTrait;
    use IronRulesTrait;
    use GuidelinesTrait;
    use ExtractAttributesTrait;
    use ResponseTrait;
    use DeterminismTrait;

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
