<?php

declare(strict_types=1);

namespace BrainCore\Architectures;

use Bfg\Dto\Collections\DtoCollection;
use Bfg\Dto\Dto;
use BrainCore\Attributes\MetaAttr;
use BrainCore\Attributes\PurposeAttr;
use BrainCore\Blueprints\Purpose;
use BrainCore\Cortex\IronRules;
use BrainCore\Cortex\Metas;

abstract class ArchetypeArchitecture extends Dto
{
    /**
     * @param  string  $element
     * @param  \Bfg\Dto\Collections\DtoCollection<int, Dto>  $child
     */
    public function __construct(
        protected string $element,
        protected DtoCollection $child,
    ) {
        static::on('created', function () {
            $this->extractAttributes();
            $this->handle();
        });
    }

    /**
     * Meta information about the architecture.
     *
     * @return \BrainCore\Cortex\Metas
     */
    public function metas(): Metas
    {
        $exists = $this->child->firstWhere(fn ($item) => $item instanceof Metas);
        if ($exists instanceof Metas) {
            return $exists;
        }
        $this->child->add(
            $metas = Metas::fromEmpty()
        );
        return $metas;
    }

    /**
     * Fixes the single goal of the profile.
     *
     * @param  non-empty-string  $text
     * @return static
     */
    public function purpose(string $text): static
    {
        $exists = $this->child->firstWhere(fn ($item) => $item instanceof Purpose);
        if ($exists instanceof Purpose) {
            $text = $exists->get('text') . PHP_EOL . $text;
            $exists->set('text', $text);
        } else {
            $this->child->add(
                Purpose::fromAssoc(compact('text'))
            );
        }
        return $this;
    }

    /**
     * Strict prohibitions/requirements with consequences for violation.
     *
     * @return \BrainCore\Cortex\IronRules
     */
    public function ironRules(): IronRules
    {
        $exists = $this->child->firstWhere(fn ($item) => $item instanceof IronRules);
        if ($exists instanceof IronRules) {
            return $exists;
        }
        $this->child->add(
            $ironRules = IronRules::fromEmpty()
        );
        return $ironRules;
    }

    /**
     * Handle the architecture logic.
     *
     * @return void
     */
    abstract protected function handle(): void;

    /**
     * Extract class attributes.
     *
     * @return void
     */
    private function extractAttributes(): void
    {
        $reflection = static::reflection();
        $metaAttributes = $reflection->getAttributes(MetaAttr::class);
        $purposeAttributes = $reflection->getAttributes(PurposeAttr::class);

        foreach ($metaAttributes as $attribute) {
            /** @var MetaAttr $metaInstance */
            $metaInstance = $attribute->newInstance();
            $this->metas()->meta($metaInstance->name)
                ->text($metaInstance->getText());
        }

        foreach ($purposeAttributes as $attribute) {
            /** @var PurposeAttr $purposeInstance */
            $purposeInstance = $attribute->newInstance();
            $this->purpose(
                $purposeInstance->getPurpose()
            );
        }
    }
}
