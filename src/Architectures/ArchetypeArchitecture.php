<?php

declare(strict_types=1);

namespace BrainCore\Architectures;

use Bfg\Dto\Dto;

abstract class ArchetypeArchitecture extends Dto
{
    /**
     * @param  string  $element
     */
    public function __construct(
        public string $element,
    ) {
    }
}
