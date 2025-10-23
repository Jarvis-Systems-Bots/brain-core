<?php

declare(strict_types=1);

namespace BrainCore\Archetypes;

use BrainCore\Architectures\ArchetypeArchitecture;
use BrainCore\Cortex\IronRules;

abstract class BrainArchetype extends ArchetypeArchitecture
{
    public function __construct(
        public IronRules $ironRules,
    ) {
        parent::__construct('system');
    }
}
