<?php

declare(strict_types=1);

namespace BrainCore\Attributes;

use Attribute;

/**
 * Indicates that the annotated class includes other DTO classes.
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Includes
{
    public array $classes;

    /**
     * @param  class-string<\Bfg\Dto\Dto>  ...$classes
     */
    public function __construct(
        string ...$classes,
    ) {
        $this->classes = $classes;
    }
}
