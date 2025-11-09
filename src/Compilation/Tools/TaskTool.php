<?php

declare(strict_types=1);

namespace BrainCore\Compilation\Tools;

use BrainCore\Architectures\ToolArchitecture;
use Symfony\Component\VarExporter\VarExporter;

class TaskTool extends ToolArchitecture
{
    public static function name(): string
    {
        return 'Task';
    }

    public static function agent(string $name, ...$args): string
    {
        foreach ($args as $index => $arg) {
            try{
                $args[$index] = VarExporter::export($arg);
            } catch (\Throwable) {
                $args[$index] = "'[unserializable]'";
            }
        }
        $args = count($args) > 0 ? ' ' . implode(', ', $args) : '';
        return static::name() . "(@agent-{$name}$args)";
    }
}
