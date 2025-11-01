<?php

declare(strict_types=1);

namespace BrainCore\Archetypes\Traits;

use BrainCore\Blueprints\Determinism;

trait DeterminismTrait
{
    /**
     * @return Determinism
     */
    public function determinism(): Determinism
    {
        return $this->findOrCreateOfChild(Determinism::class);
    }
}
