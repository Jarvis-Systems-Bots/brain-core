<?php

declare(strict_types=1);

namespace BrainCore\Compilation;

/**
 * The command of Brain CLI
 */
class BrainCLI
{
    const COMPILE = 'brain compile';
    const HELP = 'brain help';
    const DOCS = 'brain docs';
    const INIT = 'brain init';
    const LIST = 'brain list';
    const UPDATE = 'brain update';
    const MAKE_COMMAND = 'brain make:command';
    const MAKE_INCLUDE = 'brain make:include';
    const MAKE_MASTER = 'brain make:master';
    const MAKE_MCP = 'brain make:mcp';
    const MAKE_SKILL = 'brain make:skill';
    const MASTER_LIST = 'brain master:list';

    const INCLUDES_LIST = 'brain includes:list';

    public static function DOCS(...$args): string
    {
        $arguments = implode(' ', $args);
        return static::DOCS
            . (! empty($arguments) ? " $arguments" : '');
    }

    public static function MAKE_COMMAND(...$args): string
    {
        $arguments = implode(' ', $args);
        return static::MAKE_COMMAND
            . (! empty($arguments) ? " $arguments" : '');
    }

    public static function MAKE_INCLUDE(...$args): string
    {
        $arguments = implode(' ', $args);
        return static::MAKE_INCLUDE
            . (! empty($arguments) ? " $arguments" : '');
    }

    public static function MAKE_MASTER(...$args): string
    {
        $arguments = implode(' ', $args);
        return static::MAKE_MASTER
            . (! empty($arguments) ? " $arguments" : '');
    }

    public static function MAKE_MCP(...$args): string
    {
        $arguments = implode(' ', $args);
        return static::MAKE_MCP
            . (! empty($arguments) ? " $arguments" : '');
    }

    public static function MAKE_SKILL(...$args): string
    {
        $arguments = implode(' ', $args);
        return static::MAKE_SKILL
            . (! empty($arguments) ? " $arguments" : '');
    }
}
