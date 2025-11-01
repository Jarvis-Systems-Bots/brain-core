<?php

declare(strict_types=1);

namespace BrainCore\Architectures;

use Bfg\Dto\Collections\DtoCollection;
use Bfg\Dto\Dto;
use BrainCore\Architectures\Traits\FactoryHelpersTrait;

abstract class ArchetypeArchitecture extends Dto
{
    use FactoryHelpersTrait;

    /**
     * @param  string  $element
     * @param  \Bfg\Dto\Collections\DtoCollection<int, Dto>  $child
     * @param  \Bfg\Dto\Collections\DtoCollection<int, ArchetypeArchitecture>  $includes
     */
    public function __construct(
        protected string $element,
        protected DtoCollection $child,
        protected DtoCollection $includes,
    ) {
        static::on('created', function () {
            if (method_exists($this, 'extractAttributes')) {
                $this->extractAttributes();
            }
            $this->handle();
        });
    }

    /**
     * @param  ArchetypeArchitecture|class-string<ArchetypeArchitecture>  $class
     * @return static
     */
    public function include(ArchetypeArchitecture|string $class): static
    {
        $classNamespace = is_object($class) ? get_class($class) : $class;

        if (static::class === $classNamespace) {
            return $this;
        }

        if (is_string($class)) {
            $class = $class::fromEmpty();
        }

         $this->includes->add($class);

        return $this;
    }

    /**
     * Extract structured array.
     *
     * @return array<string, mixed>
     */
    public function extract(): array
    {
        $array = $this->toArray();
    }

    /**
     * Default element name.
     *
     * @return non-empty-string
     */
    abstract protected static function defaultElement(): string;

    /**
     * Handle the architecture logic.
     *
     * @return void
     */
    abstract protected function handle(): void;
}
