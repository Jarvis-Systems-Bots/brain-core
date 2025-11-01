<?php

declare(strict_types=1);

namespace BrainCore\Blueprints;

use BrainCore\Architectures\BlueprintArchitecture;
use BrainCore\Blueprints\Determinism\Ordering;
use BrainCore\Blueprints\Determinism\Randomness;

class Determinism extends BlueprintArchitecture
{
    /**
     * Set default element
     *
     * @return non-empty-string
     */
    protected static function defaultElement(): string
    {
        return 'determinism';
    }

    /**
     * @param  string  $text
     * @return static
     */
    public function ordering(string $text): static
    {
        $this->child->add(
            Ordering::fromAssoc(
                compact('text')
            )
        );

        return $this;
    }

    /**
     * @param  string  $text
     * @return static
     */
    public function randomness(string $text): static
    {
        $this->child->add(
            Randomness::fromAssoc(
                compact('text')
            )
        );

        return $this;
    }
}
