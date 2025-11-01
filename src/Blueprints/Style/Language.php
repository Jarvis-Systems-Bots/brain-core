<?php

declare(strict_types=1);

namespace BrainCore\Blueprints\Style;

use BrainCore\Architectures\BlueprintArchitecture;

class Language extends BlueprintArchitecture
{
    /**
     * Set default element
     *
     * @return non-empty-string
     */
    protected static function defaultElement(): string
    {
        return 'language';
    }
}
