<?php

declare(strict_types=1);

namespace BrainCore\Blueprints\Response\Sections;

use BrainCore\Architectures\BlueprintArchitecture;

class Section extends BlueprintArchitecture
{
    /**
     * Is single element
     *
     * @var bool
     */
    protected bool $single = true;

    /**
     * @param  non-empty-string  $name
     * @param  non-empty-string|null  $brief
     * @param  bool  $required
     */
    public function __construct(
        protected string $name,
        protected string|null $brief = null,
        protected bool $required = false,
    ) {
    }

    /**
     * Set default element
     *
     * @return non-empty-string
     */
    protected static function defaultElement(): string
    {
        return 'section';
    }

    /**
     * Set Brief
     *
     * @param  non-empty-string  $brief
     * @return static
     */
    public function brief(string $brief): static
    {
        $this->brief = $brief;

        return $this;
    }

    /**
     * Set Required
     *
     * @param  bool  $required
     * @return $this
     */
    public function required(bool $required = true): static
    {
        $this->required = $required;

        return $this;
    }
}
