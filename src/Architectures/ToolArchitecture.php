<?php

declare(strict_types=1);

namespace BrainCore\Architectures;

use BrainCore\Compilation\Traits\CompileStandartsTrait;
use Symfony\Component\VarExporter\VarExporter;

abstract class ToolArchitecture
{
    use CompileStandartsTrait;

    abstract public static function name(): string;

    public static function describe(string|array $command, ...$steps): string
    {
        return static::generateOperator(
            static::name(),
            $command,
            $steps,
            concatBody: true
        );
    }

    public static function call(...$parameters): string
    {
        return static::generateOperator(
            static::name(),
            static::parametersToString($parameters)
        );
    }
}
