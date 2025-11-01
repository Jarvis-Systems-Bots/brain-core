<?php

declare(strict_types=1);

namespace BrainCore\Blueprints\Determinism;

use BrainCore\Architectures\BlueprintArchitecture;

class Ordering extends BlueprintArchitecture
{
    /**
     * Set default element
     *
     * @return non-empty-string
     */
    protected static function defaultElement(): string
    {
        return 'ordering';
    }
}
