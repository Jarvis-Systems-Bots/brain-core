<?php

declare(strict_types=1);

namespace BrainCore\Archetypes\Traits;

use BrainCore\Attributes\Includes;
use BrainCore\Attributes\Meta;
use BrainCore\Attributes\Purpose;

trait ExtractAttributesTrait
{
    /**
     * Extract class attributes.
     *
     * @return void
     */
    protected function extractAttributes(): void
    {
        $reflection = static::reflection();
        $metaAttributes = $reflection->getAttributes(Meta::class);
        $purposeAttributes = $reflection->getAttributes(Purpose::class);
        $includesAttributes = $reflection->getAttributes(Includes::class);

        foreach ($metaAttributes as $attribute) {
            /** @var Meta $metaInstance */
            $metaInstance = $attribute->newInstance();
            $this->metas()->meta($metaInstance->name)
                ->text($metaInstance->getText());
        }

        foreach ($purposeAttributes as $attribute) {
            /** @var Purpose $purposeInstance */
            $purposeInstance = $attribute->newInstance();
            $this->purpose(
                $purposeInstance->getPurpose()
            );
        }

        foreach ($includesAttributes as $attribute) {
            /** @var Includes $includesInstance */
            $includesInstance = $attribute->newInstance();
            foreach ($includesInstance->classes as $includeClass) {
                $this->include($includeClass);
            }
        }
    }
}
