<?php

declare(strict_types=1);

namespace BrainCore\Archetypes;

use BrainCore\Architectures\ArchetypeArchitecture;

abstract class BrainArchetype extends ArchetypeArchitecture
{
    /**
     * Default element name.
     *
     * @return string
     */
    protected static function defaultElement(): string
    {
        return 'system';
    }
}
