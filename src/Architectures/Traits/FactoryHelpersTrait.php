<?php

declare(strict_types=1);

namespace BrainCore\Architectures\Traits;

use Bfg\Dto\Dto;

trait FactoryHelpersTrait
{
    /**
     * @template TClass of Dto<null>
     * @param  class-string<TClass>  $class
     * @param  non-empty-string|null  $id
     * @return TClass
     */
    protected function findOrCreateChild(string $class, string|null $id = null): Dto
    {
        $child = $this->findChild($class, $id);

        if (! $child) {
            $this->child->add(
                $child = $class::fromEmpty()
            );
            if ($id) {
                $child->set('id', $id);
            }
        }

        return $child;
    }

    /**
     * @template TClass of Dto<null>
     * @param  class-string<TClass>  $class
     * @param  non-empty-string|null  $id
     * @return TClass|null
     */
    protected function findChild(string $class, string|null $id = null): Dto|null
    {
        $child = $this->child->firstWhere(
            fn ($item) => is_a($item, $class, true)
                && ($id === null || $item->isEquals('id', $id))
        );
        if (is_a($child, $class, true)) {
            return $child;
        }
        return null;
    }

    /**
     * @template TClass of Dto<null>
     * @param  class-string<TClass>  $class
     * @param  mixed  ...$attributes
     * @return TClass
     */
    protected function createOfChild(string $class, ...$attributes): Dto
    {
        $this->child->add(
            $child = $class::new(...$attributes)
        );

        return $child;
    }
}
