<?php

declare(strict_types=1);

namespace BrainCore;

use BackedEnum;

class TomlBuilder
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
    public static function from(array $structure): string
    {
        return (new static($structure))->build();
    }

    protected function build(): string
    {
        $lines = $this->renderTable([], $this->structure, true);
        $lines = $this->trimBlankEdges($lines);

        return implode("\n", $lines);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<int, string>
     */
    protected function renderTable(array $path, array $data, bool $isRoot = false): array
    {
        $lines = [];

        [$scalars, $children, $arraysOfTables] = $this->partition($data);

        $shouldRenderHeader = ! $isRoot && (! empty($scalars) || ! empty($arraysOfTables));

        if ($shouldRenderHeader) {
            $lines[] = '[' . $this->joinPath($path) . ']';
        }

        foreach ($scalars as $key => $value) {
            $lines[] = $this->formatKeyValue($key, $value);
        }

        $firstChild = true;

        foreach ($children as $key => $value) {
            if ($shouldRenderHeader || ! empty($lines)) {
                if (! empty($lines) && end($lines) !== '') {
                    $lines[] = '';
                }
            } elseif (! $firstChild) {
                $lines[] = '';
            }

            $childLines = $this->renderTable(array_merge($path, [$key]), $value);
            $lines = array_merge($lines, $childLines);
            $firstChild = false;
        }

        foreach ($arraysOfTables as $key => $items) {
            foreach ($items as $index => $item) {
                if (! empty($lines) && end($lines) !== '') {
                    $lines[] = '';
                }

                $lines[] = '[[' . $this->joinPath(array_merge($path, [$key])) . ']]';

                [$rowScalars, $rowChildren, $rowArraysOfTables] = $this->partition($item);

                foreach ($rowScalars as $rowKey => $rowValue) {
                    $lines[] = $this->formatKeyValue($rowKey, $rowValue);
                }

                if (! empty($rowChildren) || ! empty($rowArraysOfTables)) {
                    $nestedPath = array_merge($path, [$key]);
                    $lines[] = '';
                    $lines = array_merge(
                        $lines,
                        $this->renderNestedTables($nestedPath, $rowChildren, $rowArraysOfTables)
                    );
                }

                if ($index !== array_key_last($items)) {
                    $lines[] = '';
                }
            }
        }

        return $lines;
    }

    /**
     * @param  array<string, array<string, mixed>>  $children
     * @param  array<string, array<int, array<string, mixed>>>  $arraysOfTables
     * @return array<int, string>
     */
    protected function renderNestedTables(array $path, array $children, array $arraysOfTables): array
    {
        $lines = [];

        foreach ($children as $key => $value) {
            $lines[] = '[' . $this->joinPath(array_merge($path, [$key])) . ']';

            [$scalars, $subChildren, $subArraysOfTables] = $this->partition($value);

            foreach ($scalars as $scalarKey => $scalarValue) {
                $lines[] = $this->formatKeyValue($scalarKey, $scalarValue);
            }

            if (! empty($subChildren) || ! empty($subArraysOfTables)) {
                $lines[] = '';
                $lines = array_merge(
                    $lines,
                    $this->renderNestedTables(array_merge($path, [$key]), $subChildren, $subArraysOfTables)
                );
            }
        }

        foreach ($arraysOfTables as $key => $items) {
            foreach ($items as $index => $item) {
                $lines[] = '[[' . $this->joinPath(array_merge($path, [$key])) . ']]';

                [$scalars, $subChildren, $subArraysOfTables] = $this->partition($item);

                foreach ($scalars as $scalarKey => $scalarValue) {
                    $lines[] = $this->formatKeyValue($scalarKey, $scalarValue);
                }

                if (! empty($subChildren) || ! empty($subArraysOfTables)) {
                    $lines[] = '';
                    $lines = array_merge(
                        $lines,
                        $this->renderNestedTables(array_merge($path, [$key]), $subChildren, $subArraysOfTables)
                    );
                }

                if ($index !== array_key_last($items)) {
                    $lines[] = '';
                }
            }
        }

        return $lines;
    }

    /**
     * @return array{0: array<string, mixed>, 1: array<string, array<string, mixed>>, 2: array<string, array<int, array<string, mixed>>>}
     */
    protected function partition(array $data): array
    {
        $scalars = [];
        $children = [];
        $arraysOfTables = [];

        foreach ($data as $key => $value) {
            if ($value === null) {
                continue;
            }

            if (is_array($value)) {
                if ($this->isAssoc($value)) {
                    $children[$key] = $value;
                } elseif ($this->isArrayOfTables($value)) {
                    $arraysOfTables[$key] = $value;
                } else {
                    $scalars[$key] = array_map([$this, 'normalizeScalar'], $value);
                }
                continue;
            }

            $scalars[$key] = $this->normalizeScalar($value);
        }

        return [$scalars, $children, $arraysOfTables];
    }

    protected function formatKeyValue(string $key, mixed $value): string
    {
        return $key . ' = ' . $this->formatValue($value);
    }

    protected function formatValue(mixed $value): string
    {
        if (is_array($value)) {
            $elements = array_map(fn ($item) => $this->formatValue($item), $value);
            return '[' . implode(', ', $elements) . ']';
        }

        if ($value instanceof BackedEnum) {
            $value = $value->value;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('c');
        }

        if ($value instanceof \Stringable) {
            $value = (string) $value;
        }

        return match (true) {
            is_bool($value) => $value ? 'true' : 'false',
            is_int($value), is_float($value) => (string) $value,
            default => '"' . $this->escapeString((string) $value) . '"',
        };
    }

    /**
     * @param  array<int, string>  $lines
     * @return array<int, string>
     */
    protected function trimBlankEdges(array $lines): array
    {
        while (! empty($lines) && $lines[0] === '') {
            array_shift($lines);
        }

        while (! empty($lines) && end($lines) === '') {
            array_pop($lines);
        }

        return $lines;
    }

    protected function escapeString(string $value): string
    {
        $replacements = [
            '\\' => '\\\\',
            '"' => '\\"',
            "\n" => '\\n',
            "\r" => '\\r',
            "\t" => '\\t',
        ];

        return strtr($value, $replacements);
    }

    protected function normalizeScalar(mixed $value): mixed
    {
        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        if ($value instanceof \Stringable) {
            return (string) $value;
        }

        return $value;
    }

    protected function isAssoc(array $value): bool
    {
        return array_keys($value) !== range(0, count($value) - 1);
    }

    protected function isArrayOfTables(array $value): bool
    {
        if ($value === []) {
            return false;
        }

        if ($this->isAssoc($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (! is_array($item) || ! $this->isAssoc($item)) {
                return false;
            }
        }

        return true;
    }

    protected function joinPath(array $segments): string
    {
        return implode('.', $segments);
    }
}
