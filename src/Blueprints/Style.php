<?php

declare(strict_types=1);

namespace BrainCore\Blueprints;

use BrainCore\Architectures\BlueprintArchitecture;
use BrainCore\Blueprints\Style\Brevity;
use BrainCore\Blueprints\Style\ForbiddenPhrases;
use BrainCore\Blueprints\Style\Formatting;
use BrainCore\Blueprints\Style\Language;
use BrainCore\Blueprints\Style\Tone;

class Style extends BlueprintArchitecture
{
    /**
     * Set default element
     *
     * @return non-empty-string
     */
    protected static function defaultElement(): string
    {
        return 'style';
    }

    /**
     * @param  non-empty-string  $text
     * @return $this
     */
    public function language(string $text): static
    {
        $this->child->add(
            Language::fromAssoc(compact('text'))
        );

        return $this;
    }

    /**
     * @param  non-empty-string  $text
     * @return $this
     */
    public function tone(string $text): static
    {
        $this->child->add(
            Tone::fromAssoc(compact('text'))
        );

        return $this;
    }

    /**
     * @param  non-empty-string  $text
     * @return $this
     */
    public function brevity(string $text): static
    {
        $this->child->add(
            Brevity::fromAssoc(compact('text'))
        );

        return $this;
    }

    /**
     * @param  non-empty-string  $text
     * @return $this
     */
    public function formatting(string $text): static
    {
        $this->child->add(
            Formatting::fromAssoc(compact('text'))
        );

        return $this;
    }

    /**
     * @return ForbiddenPhrases
     */
    public function forbiddenPhrases(): ForbiddenPhrases
    {
        $exists = $this->child->firstWhere(
            fn ($item) => $item instanceof ForbiddenPhrases
        );

        if (! $exists instanceof ForbiddenPhrases) {
            $this->child->add(
                $exists = ForbiddenPhrases::fromEmpty()
            );
        }

        return $exists;
    }
}
