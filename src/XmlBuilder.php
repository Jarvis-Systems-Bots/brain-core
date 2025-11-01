<?php

declare(strict_types=1);

namespace BrainCore;

use BackedEnum;

class XmlBuilder
{
    /**
     * @param  array<string, mixed>  $structure
     */
    public function __construct(
        protected array $structure
    ) {
    }

    /**
     * @param  array<string, mixed>  $structure
     */
    public static function from(array $structure): static
    {
        return new static($structure);
    }

    public function build(): string
    {
        return $this->renderNode($this->structure, true);
    }

    /**
     * @param  array<string, mixed>  $node
     */
    protected function renderNode(array $node, bool $isRoot = false): string
    {
        $element = $node['element'] ?? '';

        if ($element === '') {
            return '';
        }

        [$attributes, $cleanNode] = $this->extractAttributes($node);

        $text = $cleanNode['text'] ?? null;
        $children = isset($cleanNode['child']) && is_array($cleanNode['child'])
            ? $cleanNode['child']
            : [];
        $single = (bool) ($cleanNode['single'] ?? false);

        if ($single && $this->isEmptyContent($text, $children)) {
            return '<' . $element . $attributes . '/>';
        }

        if ($this->hasInlineText($text, $children)) {
            return '<' . $element . $attributes . '>'
                . $this->escape((string) $text)
                . '</' . $element . '>';
        }

        $lines = [];
        $lines[] = '<' . $element . $attributes . '>';

        if ($text !== null && $text !== '') {
            $lines[] = $this->escape((string) $text);
        }

        $firstChildRendered = false;

        foreach ($children as $child) {
            if (! is_array($child)) {
                continue;
            }

            if ($firstChildRendered && $isRoot && $element === 'system') {
                $lines[] = '';
            }

            $lines[] = $this->renderNode($child, false);
            $firstChildRendered = true;
        }

        $lines[] = '</' . $element . '>';

        return implode("\n", $lines);
    }

    /**
     * @param  array<string, mixed>  $node
     * @return array{0: string, 1: array<string, mixed>}
     */
    protected function extractAttributes(array $node): array
    {
        $attributes = [];
        $cleanNode = $node;

        foreach ($node as $key => $value) {
            if (in_array($key, ['element', 'child', 'text', 'single'], true)) {
                continue;
            }

            if ($this->isAttributeValue($value)) {
                $attributes[] = $this->formatAttribute($key, $value);
                unset($cleanNode[$key]);
                continue;
            }

            if ($value === null) {
                unset($cleanNode[$key]);
                continue;
            }

            if (is_array($value)) {
                if (! isset($cleanNode['child']) || ! is_array($cleanNode['child'])) {
                    $cleanNode['child'] = [];
                }

                $cleanNode['child'][] = $value;
                unset($cleanNode[$key]);
            }
        }

        $attributeString = $attributes ? ' ' . implode(' ', $attributes) : '';

        return [$attributeString, $cleanNode];
    }

    protected function isAttributeValue(mixed $value): bool
    {
        return is_scalar($value)
            || $value instanceof BackedEnum
            || $value instanceof \Stringable;
    }

    protected function formatAttribute(string $key, mixed $value): string
    {
        if ($value instanceof BackedEnum) {
            $value = $value->value;
        }

        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }

        return $key . '="' . $this->escape((string) $value) . '"';
    }

    /**
     * @param  array<int, mixed>  $children
     */
    protected function isEmptyContent(mixed $text, array $children): bool
    {
        return ($text === null || $text === '') && empty($children);
    }

    /**
     * @param  array<int, mixed>  $children
     */
    protected function hasInlineText(mixed $text, array $children): bool
    {
        return $text !== null && $text !== '' && empty($children);
    }

    protected function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
