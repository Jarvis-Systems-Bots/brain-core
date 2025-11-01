<?php

declare(strict_types=1);

namespace BrainCore\Archetypes\Traits;

use BrainCore\Blueprints\Style;

trait StyleTrait
{
    /**
     * Style and format.
     *
     * @return Style
     */
    public function style(): Style
    {
        return $this->findOrCreateOfChild(Style::class);
    }
}
