<?php

declare(strict_types=1);

namespace BrainCore\Blueprints\IronRule;

use BrainCore\Architectures\BlueprintArchitecture;

class OnViolation extends BlueprintArchitecture
{
    /**
     * Set default element
     *
     * @return non-empty-string
     */
    protected static function defaultElement(): string
    {
        return 'on_violation';
    }
}
