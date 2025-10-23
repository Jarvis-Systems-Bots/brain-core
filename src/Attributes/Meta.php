<?php

declare(strict_types=1);

namespace BrainCore\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Meta
{
    /**
     * @param  non-empty-string|list<string>  $text
     */
    public function __construct(
        public string $name,
        public string|array $text,
    ) {
    }
}
