<?php

declare(strict_types=1);

namespace BrainCore\Attributes;

use Attribute;

/**
 * Fixes the single goal of the profile.
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Purpose
{
    /**
     * @param  non-empty-string|list<string>  $text
     */
    public function __construct(
        public string|array $text,
    ) {
    }

    /**
     * Get the purpose text.
     *
     * @return non-empty-string
     */
    public function getPurpose(): string
    {
        return is_array($this->text)
            ? implode(PHP_EOL, $this->text)
            : $this->text;
    }
}
