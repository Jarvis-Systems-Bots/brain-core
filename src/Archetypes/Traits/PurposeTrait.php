<?php

declare(strict_types=1);

namespace BrainCore\Archetypes\Traits;

use BrainCore\Blueprints\Purpose;

trait PurposeTrait
{
    /**
     * Fixes the single goal of the profile.
     *
     * @param  non-empty-string  $text
     * @return static
     */
    public function purpose(string $text): static
    {
        $this->createOfChild(Purpose::class, text: $text);

        $this->setMeta(['purposeText' => $text]);

        return $this;
    }
}
