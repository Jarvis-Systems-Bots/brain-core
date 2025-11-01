<?php

declare(strict_types=1);

namespace BrainCore\Blueprints\Guideline\Example;

use BrainCore\Architectures\BlueprintArchitecture;

class Phase extends BlueprintArchitecture
{
    /**
     * @param  non-empty-string|null  $name
     */
    public function __construct(
        protected string|null $name = null,
    ) {
        //
    }

    /**
     * Set default element
     *
     * @return non-empty-string
     */
    protected static function defaultElement(): string
    {
        return 'phase';
    }
}
