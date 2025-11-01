<?php

declare(strict_types=1);

namespace BrainCore\Architectures;

use Bfg\Dto\Collections\DtoCollection;
use Bfg\Dto\Dto;
use BrainCore\Architectures\Traits\FactoryHelpersTrait;

/**
 * @property-write string|null $id
 * @property-write string $element
 * @property-write DtoCollection<int, Dto<null>> $child
 * @property-write string|null $text
 */
abstract class BlueprintArchitecture extends Dto
{
    use FactoryHelpersTrait;

    /**
     * @var array<string, class-string|array<int, class-string>|string>
     */
    protected static array $extends = [
        'id' => ['string', 'null'],
        'element' => 'string',
        'text' => ['string', 'null'],
        'child' => DtoCollection::class,
    ];

    /**
     * Is single element
     *
     * @var bool
     */
    protected bool $single = false;

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

    /**
     * Set Text
     *
     * @param  non-empty-string  $text
     * @return $this
     */
    public function text(string $text): static
    {
        $this->text = $text;

        return $this;
    }
}
