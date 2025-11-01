<?php

declare(strict_types=1);

namespace BrainCore\Blueprints\Response;

use BrainCore\Architectures\BlueprintArchitecture;

class Patches extends BlueprintArchitecture
{
    /**
     * Is single element
     *
     * @var bool
     */
    protected bool $single = true;

    public function __construct(
        protected string $policy,
    ) {
    }

    /**
     * Set default element
     *
     * @return non-empty-string
     */
    protected static function defaultElement(): string
    {
        return 'patches';
    }

    /**
     * Set policy
     *
     * @param  non-empty-string  $policy
     * @return $this
     */
    public function policy(string $policy): static
    {
        $this->policy = $policy;

        return $this;
    }
}
