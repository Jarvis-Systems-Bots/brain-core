<?php

declare(strict_types=1);

namespace BrainCore\Blueprints;

use BrainCore\Architectures\BlueprintArchitecture;
use BrainCore\Blueprints\Response\CodeBlocks;
use BrainCore\Blueprints\Response\Patches;
use BrainCore\Blueprints\Response\Sections;

class Response extends BlueprintArchitecture
{
    /**
     * Set default element
     *
     * @return non-empty-string
     */
    protected static function defaultElement(): string
    {
        return 'response_contract';
    }

    /**
     * @param  string|null  $order
     * @return Sections
     */
    public function sections(string|null $order = 'strict'): Sections
    {
        $this->child->add(
            $exists = Sections::fromAssoc(
                compact('order')
            )
        );

        return $exists;
    }

    /**
     * @param  string  $policy
     * @return static
     */
    public function codeBlocks(string $policy): static
    {
        $this->child->add(
            CodeBlocks::fromAssoc(
                compact('policy')
            )
        );

        return $this;
    }

    /**
     * @param  string  $policy
     * @return static
     */
    public function patches(string $policy): static
    {
        $this->child->add(
            Patches::fromAssoc(
                compact('policy')
            )
        );

        return $this;
    }
}
