<?php

declare(strict_types=1);

namespace BrainCore\Cortex;

use BrainCore\Architectures\CortexArchitecture;
use BrainCore\Blueprints\Meta;

/**
 * Strict prohibitions/requirements with consequences for violation.
 */
class Metas extends CortexArchitecture
{
    /**
     * Set default element
     *
     * @return non-empty-string
     */
    protected static function defaultElement(): string
    {
        return 'meta';
    }

    /**
     * Add Meta
     *
     * @param  non-empty-string  $name
     * @return \BrainCore\Blueprints\Meta
     */
    public function meta(string $name): Meta {

        $this->child->add(
            $meta = Meta::fromAssoc([
                'element' => $name,
            ])
        );

        return $meta;
    }
}
