<?php

declare(strict_types=1);

namespace BrainCore\Architectures\Traits;

use BrainCore\Attributes\Meta;

trait ExtractMetaAttributesTrait
{
    /**
     * Extract class attributes.
     *
     * @return void
     */
    protected function extractMetaAttributes(): void
    {
        $reflection = static::reflection();
        $metaAttributes = $reflection->getAttributes(Meta::class);

        foreach ($metaAttributes as $attribute) {
            /** @var Meta $metaInstance */
            $metaInstance = $attribute->newInstance();
            if (method_exists($this, 'metas')) {
                $this->metas()->meta($metaInstance->name)
                    ->text($metaInstance->getText());
            }
            $this->setMeta([$metaInstance->name => $metaInstance->getText()]);
        }
    }
}
