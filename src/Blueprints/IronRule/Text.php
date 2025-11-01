<?php

declare(strict_types=1);

namespace BrainCore\Blueprints\IronRule;

use BrainCore\Architectures\BlueprintArchitecture;

class Text extends BlueprintArchitecture
{
    /**
     * Set default element
     *
     * @return non-empty-string
     */
    protected static function defaultElement(): string
    {
        return 'text';
    }
}
