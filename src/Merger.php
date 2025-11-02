<?php

declare(strict_types=1);

namespace BrainCore;

use BackedEnum;
use Bfg\Dto\Dto;

class Merger
{
    /**
     * @param  array<string, mixed>  $structure
     */
    public function __construct(
        protected array $structure
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    protected function handle(): array
    {
        return $this->mergeNode($this->structure);
    }

    /**
     * @param  \Bfg\Dto\Dto  $dto
     * @return array<string, mixed>
     */
    public static function from(Dto $dto): array
    {
        return (new static($dto->toArray()))->handle();
    }

    /**
     * Recursively merge includes into the structure node.
     *
     * @param  array<string, mixed>  $node
     * @return array<string, mixed>
     */
    protected function mergeNode(array $node): array
    {
        $node = $this->normalizeNode($node);

        $node['child'] = array_map(fn (array $child): array => $this->mergeNode($child), $node['child']);

        $includes = array_map(fn (array $include): array => $this->mergeNode($include), $node['includes']);

        foreach ($includes as $include) {
            $node = $this->mergeNodeData($node, $include);
        }

        unset($node['includes']);

        return $node;
    }

    /**
     * Ensure node has normalized child and includes arrays.
     *
     * @param  array<string, mixed>  $node
     * @return array<string, mixed>
     */
    protected function normalizeNode(array $node): array
    {
        $node['element'] = isset($node['element']) && is_string($node['element']) && $node['element'] !== ''
            ? $node['element']
            : 'system';

        $node['child'] = isset($node['child']) && is_array($node['child'])
            ? array_values($node['child'])
            : [];

        $node['includes'] = isset($node['includes']) && is_array($node['includes'])
            ? array_values($node['includes'])
            : [];

        return $node;
    }

    /**
     * Merge incoming node data into the base node.
     *
     * @param  array<string, mixed>  $base
     * @param  array<string, mixed>  $incoming
     * @return array<string, mixed>
     */
    protected function mergeNodeData(array $base, array $incoming): array
    {
        if (($base['element'] ?? null) !== ($incoming['element'] ?? null)) {
            if (($incoming['element'] ?? null) === 'system') {
                $incomingChildren = isset($incoming['child']) && is_array($incoming['child'])
                    ? $incoming['child']
                    : [];

                if ($incomingChildren !== []) {
                    $base['child'] = $this->mergeChildren(
                        $base['child'],
                        $incomingChildren
                    );
                }

                return $base;
            }

            $base['child'][] = $incoming;

            return $base;
        }

        $base = $this->mergeAttributes($base, $incoming);

        $base['child'] = $this->mergeChildren(
            $base['child'],
            $incoming['child'] ?? []
        );

        return $base;
    }

    /**
     * Merge scalar attributes from incoming node into the base node.
     *
     * @param  array<string, mixed>  $base
     * @param  array<string, mixed>  $incoming
     * @return array<string, mixed>
     */
    protected function mergeAttributes(array $base, array $incoming): array
    {
        foreach ($incoming as $key => $value) {
            if (in_array($key, ['child', 'includes', 'element'], true)) {
                continue;
            }

            if ($value === null) {
                continue;
            }

            if ($key === 'single') {
                $base[$key] = (($base[$key] ?? false) || (bool) $value);
                continue;
            }

            $base[$key] = $value;
        }

        return $base;
    }

    /**
     * Merge child collections.
     *
     * @param  array<int, array<string, mixed>>  $current
     * @param  array<int, array<string, mixed>>  $incoming
     * @return array<int, array<string, mixed>>
     */
    protected function mergeChildren(array $current, array $incoming): array
    {
        foreach ($incoming as $incomingChild) {
            if (! is_array($incomingChild)) {
                continue;
            }

            $match = $this->findMatchingChildIndex($current, $incomingChild);

            if ($match === null) {
                $insertIndex = $this->resolveInsertionIndex($current, $incomingChild);

                if ($insertIndex === null) {
                    $current[] = $incomingChild;
                } else {
                    array_splice($current, $insertIndex, 0, [$incomingChild]);
                }
                continue;
            }

            $current[$match] = $this->mergeNodeData($current[$match], $incomingChild);
        }

        return array_values($current);
    }

    /**
     * Find matching child index for merge.
     *
     * @param  array<int, array<string, mixed>>  $children
     * @param  array<string, mixed>  $incoming
     * @return int|null
     */
    protected function findMatchingChildIndex(array $children, array $incoming): int|null
    {
        foreach ($children as $index => $child) {
            if ($this->canMergeChildren($child, $incoming)) {
                return $index;
            }
        }

        return null;
    }

    /**
     * Determine where to insert a new child to keep logical ordering.
     *
     * @param  array<int, array<string, mixed>>  $current
     * @param  array<string, mixed>  $incoming
     * @return int|null
     */
    protected function resolveInsertionIndex(array $current, array $incoming): int|null
    {
        $element = $incoming['element'] ?? null;

        if ($element === null) {
            return null;
        }

        $lastSameElementIndex = null;

        foreach ($current as $index => $child) {
            if (($child['element'] ?? null) === $element) {
                $lastSameElementIndex = $index;
            }
        }

        return $lastSameElementIndex !== null ? $lastSameElementIndex + 1 : null;
    }

    /**
     * Determine if two child nodes should be merged.
     *
     * @param  array<string, mixed>  $current
     * @param  array<string, mixed>  $incoming
     * @return bool
     */
    protected function canMergeChildren(array $current, array $incoming): bool
    {
        if (($current['element'] ?? null) !== ($incoming['element'] ?? null)) {
            return false;
        }

        $hasIdentifier = false;

        foreach (['id', 'name', 'order', 'key'] as $identifier) {
            $currentHas = array_key_exists($identifier, $current);
            $incomingHas = array_key_exists($identifier, $incoming);

            if (! $currentHas && ! $incomingHas) {
                continue;
            }

            $currentValue = $currentHas ? $current[$identifier] : null;
            $incomingValue = $incomingHas ? $incoming[$identifier] : null;

            $currentSet = $this->isNonEmptyScalar($currentValue);
            $incomingSet = $this->isNonEmptyScalar($incomingValue);

            if ($currentSet && $incomingSet) {
                $hasIdentifier = true;

                if ($currentValue === $incomingValue) {
                    return true;
                }

                return false;
            }

            if ($currentSet xor $incomingSet) {
                $hasIdentifier = true;
                return false;
            }
        }

        if ($hasIdentifier) {
            return false;
        }

        return $this->hasChildren($current) && $this->hasChildren($incoming);
    }

    protected function isNonEmptyScalar(mixed $value): bool
    {
        if ($value instanceof BackedEnum) {
            $value = $value->value;
        }

        if ($value instanceof \Stringable) {
            $value = (string) $value;
        }

        if ($value === null) {
            return false;
        }

        if (is_string($value)) {
            return trim($value) !== '';
        }

        if (is_bool($value)) {
            return true;
        }

        if (is_scalar($value)) {
            return true;
        }

        return false;
    }

    /**
     * Check if node has nested children.
     *
     * @param  array<string, mixed>  $node
     * @return bool
     */
    protected function hasChildren(array $node): bool
    {
        return ! empty($node['child']) && is_array($node['child']);
    }
}
