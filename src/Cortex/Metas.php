<?php

declare(strict_types=1);

namespace BrainCore\Cortex;

use Bfg\Dto\Collections\DtoCollection;
use Bfg\Dto\Dto;
use BrainCore\Blueprints\Meta;

/**
 * Strict prohibitions/requirements with consequences for violation.
 */
class Metas extends Dto
{
    /**
     * @param  \Bfg\Dto\Collections\DtoCollection<int, Meta>  $child
     */
    public function __construct(
        protected string $element,
        protected DtoCollection $child,
    ) {
    }

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
