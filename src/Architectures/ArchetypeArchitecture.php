<?php

declare(strict_types=1);

namespace BrainCore\Architectures;

use Bfg\Dto\Collections\DtoCollection;
use Bfg\Dto\Dto;
use BrainCore\Attributes\Meta;
use BrainCore\Attributes\Purpose;

/**
 * @property DtoCollection<Meta> $meta
 * @property string|null $purpose
 */
abstract class ArchetypeArchitecture extends Dto
{
    protected static array $extends = [
        'meta' => DtoCollection::class,
        'purpose' => ['string', 'null']
    ];

    /**
     * @param  string  $element
     */
    public function __construct(
        public string $element,
    ) {
        static::on('created', fn () => $this->extractAttributes());
    }

    private function extractAttributes(): void
    {
        $reflection = static::reflection();
        $metaAttributes = $reflection->getAttributes(Meta::class);
        $purposeAttributes = $reflection->getAttributes(Purpose::class);

        foreach ($metaAttributes as $attribute) {
            /** @var Meta $metaInstance */
            $metaInstance = $attribute->newInstance();
            $this->meta->push([
                'name' => $metaInstance->name,
                'text' => is_array($metaInstance->text)
                    ? implode(PHP_EOL, $metaInstance->text)
                    : $metaInstance->text,
            ]);
        }

        foreach ($purposeAttributes as $attribute) {
            /** @var Purpose $purposeInstance */
            $purposeInstance = $attribute->newInstance();
            $this->purpose = ($this->purpose ? PHP_EOL : '') . (is_array($purposeInstance->text)
                ? implode(PHP_EOL, $purposeInstance->text)
                : $purposeInstance->text);
        }
    }
}
