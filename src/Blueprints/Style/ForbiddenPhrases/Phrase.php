<?php

declare(strict_types=1);

namespace BrainCore\Blueprints\Style\ForbiddenPhrases;

use BrainCore\Architectures\BlueprintArchitecture;

class Phrase extends BlueprintArchitecture
{
    /**
     * Set default element
     *
     * @return non-empty-string
     */
    protected static function defaultElement(): string
    {
        return 'phrase';
    }
}
