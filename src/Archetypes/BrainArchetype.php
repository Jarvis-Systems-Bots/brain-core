<?php

declare(strict_types=1);

namespace BrainCore\Archetypes;

use BrainCore\Archetypes\Traits\MetasTrait;
use BrainCore\Archetypes\Traits\StyleTrait;
use BrainCore\Archetypes\Traits\PurposeTrait;
use BrainCore\Archetypes\Traits\ResponseTrait;
use BrainCore\Archetypes\Traits\IronRulesTrait;
use BrainCore\Archetypes\Traits\GuidelinesTrait;
use BrainCore\Archetypes\Traits\DeterminismTrait;
use BrainCore\Architectures\ArchetypeArchitecture;
use BrainCore\Archetypes\Traits\ExtractAttributesTrait;

abstract class BrainArchetype extends ArchetypeArchitecture
{
    use MetasTrait;
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

    /**
     * Init architecture.
     *
     * @return void
     */
    protected function init(): void
    {
        $agent = $this->var('AGENT_CONST', 'CLAUDE');
        $varName = $agent . '_BRAIN_MODEL';
        $model = $this->var($varName, 'opus');
        $this->setMeta('model', $model);
    }
}
