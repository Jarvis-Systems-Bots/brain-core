<?php

declare(strict_types=1);

namespace BrainCore\Architectures;

use Bfg\Dto\Collections\DtoCollection;
use Bfg\Dto\Dto;
use BrainCore\Architectures\Traits\FactoryHelpersTrait;

/**
 * @property-write string|null $id
 * @property-write string $element
 * @property-write DtoCollection<int, Dto> $child
 */
abstract class CortexArchitecture extends Dto
{
    use FactoryHelpersTrait;

    protected static array $extends = [
        'id' => ['string', 'null'],
        'element' => 'string',
        'child' => DtoCollection::class,
    ];

    /**
     * Default element name.
     *
     * @return non-empty-string
     */
    abstract protected static function defaultElement(): string;

    /**
     * Set ID
     *
     * @param  non-empty-string|null  $id
     * @return static
     */
    public function id(string|null $id = null): static
    {
        $this->id = $id;

        return $this;
    }
}
