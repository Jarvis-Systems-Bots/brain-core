<?php

declare(strict_types=1);

namespace BrainCore;

use BackedEnum;

class XmlBuilder
{
    private static array $buildCache = [];

    private static array $cache = [];

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
    public static function from(array $structure): string
    {
        // Cache by structure hash for identical structures
        $cacheKey = md5(serialize($structure));

        if (isset(self::$buildCache[$cacheKey])) {
            return self::$buildCache[$cacheKey];
        }

        $result = (new static($structure))->build();
        self::$buildCache[$cacheKey] = $result;

        return $result;
    }

    protected function build(): string
    {
        return $this->renderNode($this->structure, true);
    }

    /**
     * @param  array<string, mixed>  $node
     */
    protected function renderNode(array $node, bool $isRoot = false, int $i = 0): string
    {
        $element = $node['element'] ?? '';
        $scc = max($i - 3, 0);
        $sccNext = $scc+1;

        if ($element === '') {
            return '';
        }

        [$attributes, $cleanNode, $params] = $this->extractAttributes($node);

//        if ($element === 'guideline') {
//            dd($cleanNode, $params);
//        }

        $text = $cleanNode['text'] ?? null;
        $children = isset($cleanNode['child']) && is_array($cleanNode['child'])
            ? $cleanNode['child']
            : [];
        $single = (bool) ($cleanNode['single'] ?? false);

        if ($single && $this->isEmptyContent($text, $children)) {
            return '<' . $element . $attributes . '/>';
        }

        if ($this->hasInlineText($text, $children)) {
            $str = '<' . $element . $attributes . '>';
            $str .= $this->escape((string) $text);
            return $str . '</' . $element . '>';
        }

        $lines = [];
        $examples = [];
        if ($element === 'iron_rules') {
            $lines[] = "";
            $lines[] = MD::fromArray([
                'Iron Rules' => null
            ], $scc);
        } elseif ($element === 'guideline') {
            $lines[] = MD::fromArray([
                (isset($params['id']) && $params['id'] ? str_replace(['_','-'], ' ', $params['id']) : 'Guideline') => null
            ], $scc);
        } elseif ($element === 'rule') {
            $lines[] = MD::fromArray([
                (isset($params['id']) && $params['id'] ? $params['id'] : 'Rule')
                . (isset($params['severity']) && $params['severity'] ? ' (' . strtoupper($params['severity']) . ')' : '')
                => collect($children)->where('element', 'text')->first()['text'] ?? null,
                collect($children)->where('element', '!=', 'text')->mapWithKeys(function ($child) {
                    $key = $child['element'] ?? 'item';
                    $value = $child['text'] ?? null;
                    return [$key => $value];
                })->toArray()
            ], $sccNext);
            //dd($children);
        } elseif (! isset(static::$cache['iron_rules_exists'])) {
            $lines[] = '<' . $element . $attributes . '>';
        }

        if ($text !== null && $text !== '') {
            $lines[] = $this->escape((string) $text);
        }

        if ($element === 'guidelines') {
            $lines[] = '';
        }

        $firstChildRendered = false;
        $iron_rules_exists = false;

        foreach ($children as $child) {
            if (! is_array($child)) {
                continue;
            }
            if (isset($child['element']) && $child['element'] === 'iron_rules') {
                $iron_rules_exists = true;
            }

            if ($firstChildRendered && $isRoot && $element === 'system') {
                $lines[] = '';
            }

            if ($element === 'guideline') {
                if ($child['element'] === 'text') {
                    $lines[] = MD::fromArray([
                        $child['text']
                    ], $sccNext);
                } elseif ($child['element'] === 'example') {
                    if (isset($child['text']) && trim($child['text']) !== '') {
                        $examples[] = $child['text'];
                    }
                    foreach ($child['child'] as $i => $item) {
                        if (isset($item['text']) && trim($item['text']) !== '') {
                            $key = $item['name'] ?? $i;
                            $examples[$key] = $item['text'];
                        }
                    }
                }
            } elseif ($element !== 'rule') {
                if ($iron_rules_exists) {
                    static::$cache['iron_rules_exists'] = true;
                }
                $lines[] = $this->renderNode($child, false, $i + 1);
                if ($iron_rules_exists) {
                    unset(static::$cache['iron_rules_exists']);
                }
            }
            $firstChildRendered = true;
        }

        if ($element === 'guideline') {
//            dd($lines);
        }

        if ($examples) {
            $lines[] = MD::fromArray([
                'Examples' => $examples
            ], $sccNext);
        }

        if (
            $element === 'guideline'
            || $element === 'rule'
        ) {
            //$lines[] = '---';
            $lines[] = '';
        } elseif (! isset(static::$cache['iron_rules_exists'])) {
            $lines[] = '</' . $element . '>';
        }

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
        $params = [];

        foreach ($node as $key => $value) {
            if (in_array($key, ['element', 'child', 'text', 'single'], true)) {
                continue;
            }

            if ($this->isAttributeValue($value)) {
                $attributes[] = $this->formatAttribute($key, $value);
                $params[$key] = $value;
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

        return [$attributeString, $cleanNode, $params];
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
//        return htmlspecialchars($value, ENT_XML1, 'UTF-8');
//        return htmlspecialchars($value, 0, 'UTF-8');
        return $value;
    }
}
