<?php

declare(strict_types=1);

namespace BrainCore\Blueprints\Style;

use BrainCore\Architectures\BlueprintArchitecture;
use BrainCore\Blueprints\Style\ForbiddenPhrases\Phrase;

class ForbiddenPhrases extends BlueprintArchitecture
{
    /**
     * Set default element
     *
     * @return non-empty-string
     */
    protected static function defaultElement(): string
    {
        return 'forbidden_phrases';
    }

    /**
     * Add a forbidden phrase
     *
     * @param string $text
     * @return $this
     */
    public function phrase(string $text): static
    {
        $this->child->add(
            Phrase::fromAssoc(compact('text'))
        );

        return $this;
    }
}
