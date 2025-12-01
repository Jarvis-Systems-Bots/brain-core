<?php

declare(strict_types=1);

namespace BrainCore\Compilation;

use BrainCore\Compilation\Traits\CompileStandartsTrait;

class Store
{
    use CompileStandartsTrait;

    public static function as(string $name, ...$appropriate): string
    {
        $appropriate = static::exportedArray($appropriate, ' + ');
        return static::generateOperator(
            name: 'STORE-AS',
            arguments: static::var($name) . (! empty($appropriate) ? " = $appropriate" : ''),
        );
    }

    public static function get(string $name): string
    {
        return static::generateOperator(
            name: 'STORE-GET',
            arguments: static::var($name),
        );
    }

    public static function var(string $name): string
    {
        return puzzle('store-var', $name);
    }
}
