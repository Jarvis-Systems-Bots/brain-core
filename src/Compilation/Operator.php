<?php

declare(strict_types=1);

namespace BrainCore\Compilation;

use BrainCore\Compilation\Traits\CompileStandartsTrait;

class Operator
{
    use CompileStandartsTrait;

    public static function if(string|array $condition, string|array $then, string|array|null $else = null): string
    {
        return static::generateOperator('IF', $condition, [
            'THEN', static::generateOperatorBody($then),
            $else ? 'ELSE' : null,
            $else ? static::generateOperatorBody($else) : null,
        ], true);
    }

    public static function forEach(string|array $condition, string|array $body): string
    {
        return static::generateOperator(
            name: 'FOREACH',
            arguments: $condition,
            body: static::generateOperatorBody($body),
            hybridBody: true
        );
    }

    public static function validate(string|array $condition, string|array|null $fails = null): string
    {
        return static::generateOperator('VALIDATE', $condition, [
            $fails ? 'FAILS' : null,
            $fails ? static::generateOperatorBody($fails) : null,
        ], true, separatedArgs: true);
    }

    public static function task(...$body): string
    {
        return static::generateOperator('TASK', body: $body);
    }

    public static function verify(...$args): string
    {
        return static::generateOperator('VERIFY-SUCCESS', $args);
    }

    public static function check(...$args): string
    {
        return static::generateOperator('CHECK', $args);
    }

    public static function scenario(...$args): string
    {
        return static::generateOperator('SCENARIO', $args);
    }

    public static function goal(...$args): string
    {
        return static::generateOperator('GOAL', $args);
    }

    public static function report(...$args): string
    {
        return static::generateOperator('REPORT', $args);
    }

    public static function do(...$args): string
    {
        return static::generateOperatorBodyLine($args);
    }

    public static function chain(...$args): string
    {
        return static::generateOperatorBodyLine($args);
    }

    public static function skip(...$args): string
    {
        return static::generateOperator('SKIP', $args);
    }

    public static function note(...$args): string
    {
        return static::generateOperator('NOTE', $args);
    }

    public static function context(...$args): string
    {
        return static::generateOperator('CONTEXT', $args);
    }

    public static function output(...$args): string
    {
        return static::generateOperator('OUTPUT', $args);
    }

    public static function input(...$args): string
    {
        return static::generateOperator('INPUT', $args, separatedArgs: true);
    }

    public static function delegate(string $masterId): string
    {
        if (class_exists($masterId) && method_exists($masterId, 'id')) {
            $masterId = $masterId::id();
        }

        return static::generateOperator(
            name: 'DELEGATE-TO',
            arguments: $masterId,
            body: 'DELEGATE exploration, never Glob/Read directly'
        );
    }
}
