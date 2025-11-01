<?php

declare(strict_types=1);

namespace BrainCore\Archetypes\Traits;

use BrainCore\Cortex\Metas;

trait MetasTrait
{
    /**
     * Meta information about the architecture.
     *
     * @return \BrainCore\Cortex\Metas
     */
    public function metas(): Metas
    {
        return $this->findOrCreateOfChild(Metas::class);
    }
}
