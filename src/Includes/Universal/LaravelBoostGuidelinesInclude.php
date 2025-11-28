<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Laravel Boost MCP integration for Laravel project introspection, debugging, and documentation search.
All tools are read-only or sandboxed execution. Use for project understanding, NOT for code modification.
PURPOSE
)]
class LaravelBoostGuidelinesInclude extends IncludeArchetype
{
    protected function handle(): void
    {
        $this->guideline('tools-application')
            ->text('Application info and configuration inspection.')
            ->example('application-info - PHP/Laravel versions, packages, models (USE FIRST on new chat)')->key('info')
            ->example('get-config - Config value by dot notation (app.name, database.default)')->key('config')
            ->example('list-available-config-keys - All config keys in dot notation')->key('config-keys')
            ->example('list-available-env-vars - Env variable names from .env')->key('env');

        $this->guideline('tools-database')
            ->text('Database schema and query inspection.')
            ->example('database-schema - Tables, columns, indexes, foreign keys')->key('schema')
            ->example('database-query - Read-only SQL (SELECT, SHOW, EXPLAIN, DESCRIBE)')->key('query')
            ->example('database-connections - Configured connection names')->key('connections');

        $this->guideline('tools-code-introspection')
            ->text('PHP class discovery and analysis without reading files.')
            ->example('class-list - Find classes by path, filter by trait/interface/method')->key('list')
            ->example('class-detail - Full class API: methods, properties, constants, inheritance')->key('detail')
            ->example('class-usages - Find all usages of class across codebase')->key('usages');

        $this->guideline('tools-routes')
            ->text('Route inspection and URL generation.')
            ->example('list-routes - All routes with filters (method, path, name, action)')->key('routes')
            ->example('get-absolute-url - Convert path or named route to absolute URL')->key('url');

        $this->guideline('tools-debugging')
            ->text('Error tracking and log inspection.')
            ->example('last-error - Last exception with stack trace')->key('error')
            ->example('read-log-entries - Laravel log entries (PSR-3 formatted)')->key('logs')
            ->example('browser-logs - Frontend JS/browser logs')->key('browser')
            ->example('tinker - Execute PHP in Laravel context (like artisan tinker)')->key('tinker');

        $this->guideline('tools-docs')
            ->text('Version-specific Laravel ecosystem documentation search.')
            ->example('search-docs - Search Laravel, Livewire, Inertia, Pest, Filament docs')->key('search')
            ->example('list-artisan-commands - All registered artisan commands')->key('artisan');

        $this->guideline('workflow-new-chat')
            ->text('First action on new chat with Laravel project.')
            ->example()
            ->phase('1', 'mcp__laravel-boost__application-info() → understand stack')
            ->phase('2', 'mcp__laravel-boost__database-schema() → understand data model')
            ->phase('3', 'mcp__laravel-boost__list-routes() → understand API surface');

        $this->guideline('workflow-debugging')
            ->text('Debug workflow for Laravel errors.')
            ->example()
            ->phase('1', 'mcp__laravel-boost__last-error() → get exception details')
            ->phase('2', 'mcp__laravel-boost__read-log-entries({entries: 10}) → context')
            ->phase('3', 'mcp__laravel-boost__class-detail({class: "..."}) → inspect related class');

        $this->guideline('workflow-code-discovery')
            ->text('Understand codebase structure without file reads.')
            ->example()
            ->phase('1', 'class-list({path: "app/Models"}) → find all models')
            ->phase('2', 'class-detail({class: "App\\Models\\User"}) → inspect API')
            ->phase('3', 'class-usages({target: "App\\Models\\User"}) → find dependencies');

        $this->rule('tinker-mandatory')->critical()
            ->text('ALL PHP code MUST be tested and executed through mcp__laravel-boost__tinker. NEVER use Bash(php ...) or direct PHP execution.')
            ->why('Tinker provides Laravel context: facades, models, helpers, config. Direct PHP execution lacks application context and dependencies.')
            ->onViolation('STOP. Use mcp__laravel-boost__tinker({code: "...", timeout: 180}) instead of Bash or direct PHP.');

        $this->guideline('tinker-usage')
            ->text('PHP code execution patterns via Laravel Boost tinker.')
            ->example('Test snippet: mcp__laravel-boost__tinker({code: "return User::count();", timeout: 30})')->key('test')
            ->example('Debug value: mcp__laravel-boost__tinker({code: "return config(\'app.name\');", timeout: 30})')->key('debug')
            ->example('Validate logic: mcp__laravel-boost__tinker({code: "return (new Service)->process($data);", timeout: 60})')->key('validate')
            ->example('Check model: mcp__laravel-boost__tinker({code: "return User::first()->toArray();", timeout: 30})')->key('model');
    }
}
