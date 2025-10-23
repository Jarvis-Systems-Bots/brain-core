<?php

declare(strict_types=1);

namespace BrainCore\Blueprints\IronRule;

use Bfg\Dto\Dto;

class Text extends Dto
{
    /**
     * @param  non-empty-string  $element
     * @param  string|null  $text
     */
    public function __construct(
        protected string $element,
        protected string|null $text = null,
    ) {
    }

    /**
     * Set default element
     *
     * @return non-empty-string
     */
    protected static function defaultElement(): string
    {
        return 'text';
    }
}
