<?php

declare(strict_types=1);

namespace BrainCore\Blueprints\Guideline;

use BrainCore\Architectures\BlueprintArchitecture;
use BrainCore\Blueprints\Guideline\Example\Phase;

class Example extends BlueprintArchitecture
{
    /**
     * @param  non-empty-string|null  $key
     */
    public function __construct(
        protected string|null $key = null,
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
        return 'example';
    }

    /**
     * @param  non-empty-string  $name
     * @param  non-empty-string  $text
     * @return static
     */
    public function phase(string $name, string $text): static
    {
        $this->child->add(
            Phase::fromAssoc(
                compact('name', 'text')
            )
        );

        return $this;
    }

    /**
     * Set Other Next Example
     *
     * @param  non-empty-string|null  $text
     * @return Example
     */
    public function example(string|null $text = null): static
    {
        /** @var \BrainCore\Blueprints\Guideline|null $parent */
        $parent = $this->getMeta('parentDto');

        return $parent->example($text);
    }

    /**
     * Set Key
     *
     * @param  non-empty-string|null  $key
     * @return $this
     */
    public function key(string|null $key): static
    {
        $this->key = $key;

        return $this;
    }
}
