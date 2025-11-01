<?php

declare(strict_types=1);

namespace BrainCore\Blueprints;

use BrainCore\Architectures\BlueprintArchitecture;
use BrainCore\Blueprints\Guideline\Text;
use BrainCore\Blueprints\Guideline\Example;

class Guideline extends BlueprintArchitecture
{
    /**
     * @param  non-empty-string|null  $id
     */
    public function __construct(
        protected string|null $id,
    ) {
    }

    /**
     * Set default element
     *
     * @return non-empty-string
     */
    protected static function defaultElement(): string
    {
        return 'guideline';
    }

    /**
     * @param  non-empty-string  $text
     * @return $this
     */
    public function text(string $text): static
    {
        $this->child->add(
            Text::fromAssoc(compact('text'))
        );

        return $this;
    }

    /**
     * Set Example
     *
     * @param  non-empty-string|null  $text
     * @return Example
     */
    public function example(string|null $text = null): Example
    {
        $this->child->add(
            $example = Example::fromAssoc(compact('text'))
        );

        $example->setMeta([
            'parentDto' => $this,
        ]);

        return $example;
    }
}
